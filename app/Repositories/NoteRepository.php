<?php

namespace App\Repositories;

use App\Enums\NoteableType;

use App\Models\Note;
use Illuminate\Database\Eloquent\Collection;

class NoteRepository
{
    public function newQuery()
    {
        return Note::query();
    }
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


    public function filterByNoteableType(?string $noteableType): Collection
    {
        $query = $this->newQuery();
        
        if ($noteableType) {
            $modelClass = NoteableType::getModelClass($noteableType);
            $query->where('noteable_type', $modelClass);
        }
        
        return $query->get();
    }
}