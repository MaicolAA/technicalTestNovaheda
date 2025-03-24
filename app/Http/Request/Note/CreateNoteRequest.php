<?php

namespace App\Http\Request\Note;

use App\Enums\NoteableType;
use App\Http\Request\MainRequest;

class CreateNoteRequest extends MainRequest
{
    public function __construct()
    {
        $this->setMessage(__('messages.note_create_error'));
    }

    public function rules(): array
    {
        return [
            'note' => 'required|string',
            'noteable_type' => 'required|string|in:' . implode(',', NoteableType::values()),
            'noteable_id' => 'required|integer',
        ];
    }

    public function messages(): array
    {
        return [
            'note.required' => __('messages.note_content_required'),
            'noteable_type.required' => __('messages.noteable_type_required'),
            'noteable_type.in' => __('messages.noteable_type_invalid'),
            'noteable_id.required' => __('messages.noteable_id_required'),
        ];
    }
}