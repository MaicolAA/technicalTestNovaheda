<?php

namespace App\Http\Request\Project;

use App\Http\Request\MainRequest;

class UpdateProjectRequest extends MainRequest
{
    public function rules(): array
    {
        return [
            'name' => 'string|max:255',
            'description' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('messages.error_name_required'),
            'name.string' => __('messages.error_name_string'),
            'name.max' => __('messages.error_name_max'),
            'description.string' => __('messages.error_description_string'),
            'description.max' => __('messages.error_description_max'),
        ];
    }
}