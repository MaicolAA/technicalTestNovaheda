<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Exception;

use App\Http\Request\Note\CreateNoteRequest;
use App\Http\Request\Note\UpdateNoteRequest;
use App\Services\NoteService;
use App\Dto\NoteDto;

class NoteController extends MainController
{
    public function __construct(
        private NoteService $noteService
    ) {}


    public function store(CreateNoteRequest $request): JsonResponse
    {
        try {
            $noteDto = NoteDto::fromRequest($request->validated());
            $note = $this->noteService->createNote($noteDto);
            return $this->created(
                $note->toArray(),
                __('messages.note_created')
            );
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $withNoteable = request()->has('include') && str_contains(request('include'), 'noteable');
            $note = $this->noteService->getNote($id, $withNoteable);

            if (!$note) {
                throw new Exception(__('messages.note_not_found'), 404);
            }

            return $this->ok(
                $note->toArray(),
                __('messages.ok')
            );
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function index(): JsonResponse
    {
        try {
            $noteableType = request('noteable_type');
            $noteableId = request('noteable_id');
            
            $notes = $this->noteService->getAllNotes($noteableType, $noteableId);
            
            return $this->ok(
                $notes,
                __('messages.ok')
            );
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function update(UpdateNoteRequest $request, int $id): JsonResponse
    {
        try {
            $noteDto = NoteDto::fromRequest($request->validated());
            $noteDto->setId($id);

            $note = $this->noteService->updateNote($noteDto);
            return $this->ok(
                $note->toArray(),
                __('messages.note_updated')
            );
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $deleted = $this->noteService->deleteNote($id);
            if (!$deleted) {
                throw new Exception(__('messages.note_not_found'), 404);
            }
            return $this->message(__('messages.note_deleted'));
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }


    // public function getNotesForCompany(int $companyId): JsonResponse
    // {
    //     try {
    //         $notes = $this->noteService->getNotesForCompany($companyId);
    //         return $this->ok(
    //             $notes,
    //             __('messages.ok')
    //         );
    //     } catch (Exception $e) {
    //         return $this->error($e->getMessage(), $e->getCode());
    //     }
    // }

    // public function getNotesForContact(int $contactId): JsonResponse
    // {
    //     try {
    //         $notes = $this->noteService->getNotesForContact($contactId);
    //         return $this->ok(
    //             $notes,
    //             __('messages.ok')
    //         );
    //     } catch (Exception $e) {
    //         return $this->error($e->getMessage(), $e->getCode());
    //     }
    // }
}
