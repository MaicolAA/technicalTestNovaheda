<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;

use App\Models\Contact;

class ContactRepository
{

    public function all(): Collection
    {
        return Contact::all();
    }

    public function find(int $id)
    {
        return Contact::find($id);
    }

    public function create(array $data): Contact
    {
        return Contact::create($data);
    }

    public function update(Contact $contact, array $data): Contact
    {
        $contact->update($data);
        return $contact;
    }

    public function delete(Contact $contact): bool
    {
        return $contact->delete();
    }
}
