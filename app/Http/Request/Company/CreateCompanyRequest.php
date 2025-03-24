<?php

namespace App\Http\Request\Company;

use App\Http\Request\MainRequest;

class CreateCompanyRequest extends MainRequest
{
    public function __construct()
    {
        $this->setMessage(__('messages.company_create_error'));
    }
    
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:companies',
            'address' => 'nullable|string|max:255',
            'website' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('messages.error_name_required'),
            'name.string' => __('messages.error_name_string'),
            'email.required' => __('messages.error_email_required'),
            'email.email' => __('messages.error_email_email'),
            'email.unique' => __('messages.error_email_unique'),
            'address.nullable' => __('messages.error_address_nullable'),
            'website.nullable' => __('messages.error_website_nullable'),
            'description.nullable' => __('messages.error_description_nullable'),
        ];
    }
}