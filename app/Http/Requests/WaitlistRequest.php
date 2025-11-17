<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WaitlistRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // allow public access
    }

    public function rules(): array
    {
        return [
            'full_name' => 'required|string|max:255|min:2',
            'email' => ['required', 'email', 'unique:waitlists,email'],
            'role' => ['required', Rule::in(['talent', 'company'])],
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'This email is already on our waitlist!',
            'role.in' => 'Please select either talent or company.',
        ];
    }
}
