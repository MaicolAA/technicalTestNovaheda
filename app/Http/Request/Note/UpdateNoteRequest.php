<?php

namespace App\Http\Request\Note;

use App\Enums\NoteableType;
use App\Http\Request\MainRequest;

class UpdateNoteRequest extends MainRequest
{
    public function __construct()
    {
        $this->setMessage(__('messages.note_update_error'));
    }

    public function rules(): array
    {
        return [
            'note' => 'string',
            'noteable_type' => 'string|in:' . implode(',', NoteableType::values()),
            'noteable_id' => 'integer',
        ];
    }

    public function messages(): array
    {
        return [
            'note.string' => __('messages.note_content_string'),
            'noteable_type.string' => __('messages.noteable_type_string'),
            'noteable_type.in' => __('messages.noteable_type_invalid'),
            'noteable_id.integer' => __('messages.noteable_id_integer'),
        ];
    }
}