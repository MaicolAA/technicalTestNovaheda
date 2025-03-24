<?php

namespace App\Services;

use Exception;

use App\Repositories\NoteRepository;
use App\Enums\NoteableType;
use App\DTO\NoteDto;
use App\Models\Company;

class NoteService
{
    public function __construct(
        private NoteRepository $noteRepository
    ) {}

    public function createNote(NoteDto $noteDto): NoteDto
    {
        if (!in_array($noteDto->getNoteableType(), NoteableType::values())) {
            throw new Exception(__('messages.noteable_type_invalid', [
                'values' => implode(', ', NoteableType::values()),
            ]), 400);
        }
    
        $note = $this->noteRepository->create($noteDto->toCreate());
        return NoteDto::fromModel($note);
    }
    public function updateNote(NoteDto $noteDto): ?NoteDto
    {
        if (!in_array($noteDto->getNoteableType(), NoteableType::values())) {
            throw new Exception(__('messages.noteable_type_invalid', [
                'values' => implode(', ', NoteableType::values()),
            ]), 400);
        }

        $note = $this->noteRepository->find($noteDto->getId());
        if (!$note) {
            throw new Exception(__('messages.note_not_found'), 404);
        }

        $note = $this->noteRepository->update($note, $noteDto->toUpdate());
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

    public function getNote(int $id): ?NoteDto
    {
        $note = $this->noteRepository->find($id);
        if (!$note) {
            return null;
        }
        return NoteDto::fromModel($note);
    }

    public function getAllNotes(): array
    {
        $notes = $this->noteRepository->all();
        $response = [];
        foreach($notes as $note) $response[] =  NoteDto::fromModel($note)->toArray();
        return $response;
    }

    public function getNotesForCompany(int $companyId): array
    {
        $notes = $this->noteRepository->getNotesForCompany($companyId);
        $response = [];
        foreach($notes as $note) $response[] =  NoteDto::fromModel($note)->toArray();
        return $response;
    }

    public function getNotesForContact(int $contactId): array
    {
        $notes = $this->noteRepository->getNotesForContact($contactId);
        $response = [];
        foreach($notes as $note) $response[] =  NoteDto::fromModel($note)->toArray();
        return $response;
    }
}