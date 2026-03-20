<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends FormRequest
{
    public function rules(): array
    {
        $rules = [
            'email' => 'required|email',
            'password' => 'required|string',
        ];

        if ($this->routeIs('register')) {
            $rules['name'] = 'required|string|max:50';
            $rules['email'] .= '|unique:users';
            $rules['password'] .= '|min:8|confirmed';
        }

        return $rules;
    }
}
