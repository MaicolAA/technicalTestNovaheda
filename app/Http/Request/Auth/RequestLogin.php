<?php

namespace App\Http\Request\Auth;

use App\Http\Request\MainRequest;

class RequestLogin extends MainRequest
{
    public function __construct()
    {
        $this->setMessage(__('messages.user_login_error'));
    }
    
    public function rules(): array
    {
        return [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('messages.error_name_required'),
            'name.string' => __('messages.error_name_string'),
            'email.required' => __('messages.error_email_required'),
            'email.email' => __('messages.error_email_email'),
            'password.required' => __('messages.error_password_required'),
            'password.string' => __('messages.error_password_string'),
        ];
    }
}
