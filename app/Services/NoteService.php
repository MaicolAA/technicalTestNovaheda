<?php

namespace App\Services;

use Exception;

use App\Repositories\NoteRepository;
use App\Enums\NoteableType;
use App\DTO\NoteDto;
use App\Models\Company;
use App\Models\Note;

class NoteService
{
    private const COMPANY = "company";
    private const CONTACT = "contact";


    public function __construct(
        private NoteRepository $noteRepository,
        private readonly CompanyService $companyService,
        private readonly ContactService $contactService
    ) {}

    public function createNote(NoteDto $noteDto): NoteDto
    {
        if (!in_array($noteDto->getNoteableType(), NoteableType::values())) {
            throw new Exception(__('messages.noteable_type_invalid', [
                'values' => implode(', ', NoteableType::values()),
            ]), 400);
        }

        $this->validateExistentsModels($noteDto);

        $createDto = $noteDto->toCreate();
        // $createDto['noteable_type'] = NoteableType::getNoteable($noteDto->getNoteableType());

        $note = $this->noteRepository->create($createDto);
        return NoteDto::fromModel($note);
    }

    /**
     * Actualiza una nota
     * @param \App\DTO\NoteDto $noteDto
     * @return NoteDto
     */
    public function updateNote(NoteDto $noteDto): ?NoteDto
    {
        $note = $this->noteRepository->find($noteDto->getId());
        if (!$note) {
            throw new Exception(__('messages.note_not_found'), 404);
        }

        $this->validateExistentsModels($noteDto);

        $updateData = $noteDto->toUpdate();
        $updateData['noteable_type'] = NoteableType::getModelClass($noteDto->getNoteableType());

        $note = $this->noteRepository->update($note, $updateData);
        return NoteDto::fromModel($note);
    }

    public function deleteNote(int $id): bool
    {
        $note = $this->noteRepository->find($id);
        if (!$note) {
            throw new Exception(__('messages.note_not_found'), 404);
        }
        return $this->noteRepository->delete($note);
    }

    public function getNote(int $id, bool $withNoteable = false): ?NoteDto
    {
        $query = $this->noteRepository->newQuery();

        if ($withNoteable) {
            $query->with('noteable');
        }

        $note = $query->find($id);

        if (!$note) {
            return null;
        }

        return NoteDto::fromModel($note);
    }

    public function getAllNotes(?string $noteableType = null, ?int $noteableId = null): array
    {
        $query = $this->noteRepository->newQuery();

        if ($noteableType && $noteableId) {
            $modelClass = NoteableType::getModelClass($noteableType);
            $query->where('noteable_type', $modelClass)
                ->where('noteable_id', $noteableId);
        } elseif ($noteableType) {
            $modelClass = NoteableType::getModelClass($noteableType);
            $query->where('noteable_type', $modelClass);
        }

        if(is_null($noteableId) && is_null($noteableType))
        {
            return Note::all()->map(fn(Note $note) => NoteDto::fromModel($note)->toArray())
            ->toArray();
        }

        return $query->get()
            ->map(fn(Note $note) => NoteDto::fromModel($note)->toArray())
            ->toArray();
    }


    // public function getNotesForCompany(int $companyId): array
    // {
    //     return $this->getNotesForNoteable($companyId, NoteableType::getNoteable(self::COMPANY));
    // }


    // public function getNotesForContact(int $contactId): array
    // {
    //     return $this->getNotesForNoteable($contactId, NoteableType::getNoteable(self::CONTACT));
    // }


    // private function getNotesForNoteable(int $noteableId, string $noteableEntity): array
    // {
    //     $response = [];
    //     $notes = $this->noteRepository->getNotesNoteable($noteableId, $noteableEntity);

    //     foreach($notes as $note) 
    //     $response[] =  NoteDto::fromModel($note)->toArray();

    //     return $response;
    // }

    // En NoteService
    private function validateExistentsModels(NoteDto $noteDto): void
    {
        switch ($noteDto->getNoteableType()) {
            case self::COMPANY:
                $company = $this->companyService->getCompany($noteDto->getNoteableId());
                if (!$company) {
                    throw new Exception(__('messages.company_not_found'), 404);
                }
                break;
            case self::CONTACT:
                $contact = $this->contactService->getContact($noteDto->getNoteableId());
                if (!$contact) {
                    throw new Exception(__('messages.contact_not_found'), 404);
                }
                break;
        }
    }
}
