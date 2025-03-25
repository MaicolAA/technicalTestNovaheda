<?php

namespace App\Services;

use App\Dto\ContactDto;
use App\Repositories\ContactRepository;
use App\Repositories\CompanyRepository;
use Exception;

class ContactService
{
    public function __construct(
        private ContactRepository $contactRepository,
        private CompanyRepository $companyRepository,
    ) {}

    /**
     * @param ContactDto $contactDto
     * @return ContactDto
     */
    public function createContact(ContactDto $contactDto): ContactDto
    {
        $company = $this->companyRepository->find($contactDto->getCompany()->getId());
        if (!$company) {
            throw new Exception(__('messages.company_not_found'), 404);
        }

        $contact = $this->contactRepository->create($contactDto->toCreate());
        return ContactDto::fromModel($contact);
    }

    /**
     * @param ContactDto $contactDto
     * @return ContactDto|null
     */
    public function updateContact(ContactDto $contactDto): ?ContactDto
    {
        $contact = $this->contactRepository->find($contactDto->getId());
        if (!$contact) {
            throw new Exception(__('messages.contact_not_found'), 404);
        }
        $company = $this->companyRepository->find($contactDto->getCompany()->getId());
        if (!$company) {
            throw new Exception(__('messages.company_not_found'), 404);
        }

        $contact = $this->contactRepository->update($contact, $contactDto->toUpdate());
        return ContactDto::fromModel($contact);
    }

    /**
     * @return bool
     */
    public function deleteContact(int $id): bool
    {
        $contact = $this->contactRepository->find($id);
        if (!$contact) {
            throw new Exception(__('messages.contact_not_found'), 404);
        }
        return $this->contactRepository->delete($contact);
    }

    /**
     * @return ContactDto|null
     */
    public function getContact(int $id): ?ContactDto
    {
        $contact = $this->contactRepository->find($id);
        if (!$contact) {
            return null;
        }
        return ContactDto::fromModel($contact);
    }


    /**
     * @return ContactDto[]
     */
    public function getAllContacts(): array
    {
        $contacts = $this->contactRepository->all();

        $response = [];
        foreach ($contacts as $contact) {
            $response[] = ContactDto::fromModel($contact, false)->toList();
        }

        return $response;
    }
}
