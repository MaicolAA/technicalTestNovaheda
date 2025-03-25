<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Exception;

use App\Http\Request\Contact\CreateContactRequest;
use App\Http\Request\Contact\UpdateContactRequest;
use App\Services\ContactService;
use App\Dto\ContactDto;

class ContactController extends MainController
{
    /**
     * Inicializa la clase y los servicios que usa 
     * @param \App\Services\ContactService $contactService
     */
    public function __construct(
        private ContactService $contactService
    ) {}

    /**
     * Lista los contactos
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $contacts = $this->contactService->getAllContacts();
            return $this->ok(
                $contacts,
                __('messages.ok')
            );
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    /**
     * Crea un contacto
     * @param \App\Http\Request\Contact\CreateContactRequest $request
     * @return JsonResponse
     */
    public function store(CreateContactRequest $request): JsonResponse
    {
        try {
            $contactDto = ContactDto::fromRequest($request->validated());
            $contact = $this->contactService->createContact($contactDto);
            return $this->created(
                $contact->toArray(),
                __('messages.contact_created')
            );
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    /**
     * Retorna el detalle de un contacto
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $contact = $this->contactService->getContact($id);
            if (!$contact) {
                throw new Exception(__('messages.contact_not_found'), 404);
            }
            return $this->ok(
                $contact->toArray(),
                __('messages.ok')
            );
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Actualiza la información de un contacto
     * @param \App\Http\Request\Contact\UpdateContactRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateContactRequest $request, int $id): JsonResponse
    {
        try {
            $contactDto = ContactDto::fromRequest($request->validated())->setId($id);
            $contact = $this->contactService->updateContact($contactDto);
            return $this->ok(
                $contact->toArray(),
                __('messages.contact_updated')
            );
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Elimina un contacto
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $deleted = $this->contactService->deleteContact($id);
            if (!$deleted) {
                throw new Exception(__('messages.contact_not_found'), 404);
            }
            return $this->message(__('messages.contact_deleted'));
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
