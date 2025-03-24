<?php

namespace App\Http\Request\Contact;

use App\Http\Request\MainRequest;

class CreateContactRequest extends MainRequest
{
    public function __construct()
    {
        $this->setMessage(__('messages.contact_create_error'));
    }
    
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'email' => 'required|email|unique:contacts,email',
            'phone' => 'nullable|string|max:20',
            'company_id' => 'required|exists:companies,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('messages.contact_name_required'),
            'name.string' => __('messages.contact_name_string'),
            'name.max' => __('messages.contact_name_max'),
            'email.required' => __('messages.contact_email_required'),
            'email.email' => __('messages.contact_email_email'),
            'email.unique' => __('messages.contact_email_unique'),
            'phone.string' => __('messages.contact_phone_string'),
            'phone.max' => __('messages.contact_phone_max'),
            'company_id.required' => __('messages.contact_company_id_required'),
            'company_id.exists' => __('messages.contact_company_id_exists'),
            'address.string' => __('messages.contact_address_string'),
            'address.max' => __('messages.contact_address_max'),
        ];
    }
}