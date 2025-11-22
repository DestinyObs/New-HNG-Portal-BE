<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class GoogleAuthRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // allow guests
    }

    public function rules(): array
    {
        return [
            'token' => 'required|string',
            'role' => 'required|string|in:talent,employer'
        ];
    }

    public function messages(): array
    {
        return [
            'token.required' => 'Google token is required.',
            'role.required' => 'Role is required.',
            'role.in' => 'Role must be either talent or employer.',
        ];
    }
}
