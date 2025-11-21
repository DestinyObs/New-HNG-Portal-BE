<?php

namespace App\Http\Requests\Talent;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class UpdatePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'current_password' => ['required',],
            'password' => ['required', 'min:8', 'confirmed'],
        ];
    }


    public function messages(): array
    {
        return [
            'current_password.required' => 'Current password is required.',
            'password.required' => 'New password is required.',
            'password.min' => 'New password must be at least 8 characters long.',
            'password.confirmed' => 'New password confirmation does not match.',
        ];
    }


    //? Add a validation after rule to check current password
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (!$this->checkCurrentPassword()) {
                $validator->errors()->add('current_password', 'The current password is incorrect.');
            }
        });
    }


    //? Access database to verify current password
    private function checkCurrentPassword(): bool
    {
        $user = $this->user();
        return Hash::check($this->current_password, $user->password) ?? false;
    }
}
