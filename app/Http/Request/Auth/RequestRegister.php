<?php

namespace App\Http\Request\Auth;

use App\Http\Request\MainRequest;

class RequestRegister extends MainRequest
{
    public function __construct()
    {
        $this->setMessage(__('messages.user_register_error'));
    }
    
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
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
            'password.required' => __('messages.error_password_required'),
            'password.string' => __('messages.error_password_string'),
            'password.min' => __('messages.error_password_min'),
        ];
    }
}
