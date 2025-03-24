<?php

namespace App\Repositories;

use Exception;

use App\Models\Note;
use App\Models\Company;
use App\Models\Contact;

class NoteRepository
{
    public function all()
    {
        return Note::all();
    }

    public function find(int $id)
    {
        return Note::find($id);
    }

    public function create(array $data): Note
    {
        return Note::create($data);
    }

    public function update(Note $note, array $data): Note
    {
        $note->update($data);
        return $note;
    }

    public function delete(Note $note): bool
    {
        return $note->delete();
    }

    public function getNotesForCompany(int $companyId)
    {
        $company = Company::find($companyId);
        if (!$company) {
            throw new Exception(__('messages.company_not_found'), 404);
        }
        return $company->notes;
    }

    public function getNotesForContact(int $contactId)
    {
        $contact = Contact::find($contactId);
        if (!$contact) {
            throw new Exception(__('messages.contact_not_found'), 404);
        }
        return $contact->notes;
    }
}