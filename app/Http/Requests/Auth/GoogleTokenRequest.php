<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class GoogleTokenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'access_token' => 'required|string',
            'role' => 'nullable|string|in:talent,company,admin',
            'company_name' => 'nullable|string|max:255',
        ];
    }
}
