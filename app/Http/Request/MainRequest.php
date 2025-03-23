<?php

namespace App\Http\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class MainRequest extends FormRequest
{
    protected string $message = ''; 

    public function setMessage(string $message)
    {
        $this->message = $message;
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = collect($validator->errors()->all());

        throw new HttpResponseException(response()->json([
            'status' => 422,
            'message' => $this->message,
             'error' => $errors->values()->all()
        ], 422));
    }
}
