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
    /**
     * @param \App\Services\NoteService $noteService
     */
    public function __construct(
        private NoteService $noteService
    ) {}


    /**
     * Crea una nota
     * @param \App\Http\Request\Note\CreateNoteRequest $request
     * @return JsonResponse
     */
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

    /**
     * Presenta el detalle de una nota
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $note = $this->noteService->getNote($id);

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

    /**
     * Lista las notas, todas, por tipo y por tipo y entidad noteable
     * @return JsonResponse
     */
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

    /**
     * Actualiza una nota
     * @param \App\Http\Request\Note\UpdateNoteRequest $request
     * @param int $id
     * @return JsonResponse
     */
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

    /**
     * Elimina una nota
     * @param int $id
     * @return JsonResponse
     */
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

}
