<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AdminUserRequest extends FormRequest
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
            'firstname'  => 'sometimes|nullable|string|max:255',
            'lastname'   => 'sometimes|nullable|string|max:255',
            'othername'  => 'sometimes|nullable|string|max:255',
            'email'      => 'sometimes|nullable|email|max:255',
            'phone'      => 'sometimes|nullable|string|max:50',
            'dob'        => 'sometimes|nullable|date',
            'status'     => 'sometimes|nullable|string|in:active,inactive,banned',
            'address_id' => 'sometimes|nullable',
            'photo_url'  => 'sometimes|nullable|string',
            'role'       => 'sometimes|nullable|string|in:user,talent,admin,employer',
        ];
    }
}
