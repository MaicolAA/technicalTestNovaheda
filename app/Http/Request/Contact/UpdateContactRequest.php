<?php

namespace App\Http\Request\Contact;

use App\Http\Request\MainRequest;

class UpdateContactRequest extends MainRequest
{
    public function __construct()
    {
        $this->setMessage(__('messages.contact_update_error'));
    }
    
    public function rules(): array
    {
        return [
            'name' => 'string|max:255',
            'email' => 'email|unique:contacts,email,' . $this->route('contact'),
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'company_id' => 'exists:companies,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.string' => __('messages.contact_name_string'),
            'name.max' => __('messages.contact_name_max'),
            'email.email' => __('messages.contact_email_email'),
            'email.unique' => __('messages.contact_email_unique'),
            'phone.string' => __('messages.contact_phone_string'),
            'phone.max' => __('messages.contact_phone_max'),
            'company_id.exists' => __('messages.contact_company_id_exists'),
            'address.string' => __('messages.contact_address_string'),
            'address.max' => __('messages.contact_address_max'),
        ];
    }
}