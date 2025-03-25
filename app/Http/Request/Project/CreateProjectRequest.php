<?php

namespace App\Http\Request\Project;

use App\Http\Request\MainRequest;

class CreateProjectRequest extends MainRequest
{
    public function __construct()
    {
        $this->setMessage(__('messages.project_create_error'));
    }
    
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [

        ];
    }
}