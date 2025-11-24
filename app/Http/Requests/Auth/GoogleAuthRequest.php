<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GoogleAuthRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $mode = $this->input('mode', 'signup');

        if ($mode === 'login') {
            return [
                'email' => 'required|email|exists:users,email',
            ];
        }

        // Signup rules
        return [
            'firstname' => 'required_if:role,talent|string|max:255',
            'lastname' => 'required_if:role,talent|string|max:255',
            'company_name' => 'required_if:role,company|string|max:255|unique:companies,name',
            'email' => 'required|email|unique:users,email',
            'role' => ['required', Rule::in(['talent', 'company'])],
        ];
    }

    public function messages(): array
    {
        $mode = $this->input('mode', 'signup');

        if ($mode === 'login') {
            return [
                'email.required' => 'Email is required for login.',
                'email.exists' => 'No account found with this email.',
            ];
        }

        return [
            'firstname.required_if' => 'First name is required for talent accounts.',
            'lastname.required_if' => 'Last name is required for talent accounts.',
            'company_name.required_if' => 'Company name is required for company accounts.',
            'company_name.unique' => 'This company already exists.',
            'email.required' => 'The email field is required.',
            'email.unique' => 'This email is already registered.',
        ];
    }
}
