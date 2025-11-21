<?php

namespace App\Http\Requests\Employer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCompanyRequest extends FormRequest
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
            'user_id' => ['required', 'uuid', 'exists:users,id'],

            'name' => ['required', 'string', 'max:255'],

            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('companies', 'slug'),
            ],

            'description' => ['nullable', 'string'],

            'logo_url' => ['nullable', 'url'],

            'country_id' => [
                'nullable',
                'uuid',
                'exists:countries,id',
            ],

            'website_url' => ['nullable', 'url'],

            'is_verified' => ['sometimes', 'boolean'],

            'official_email' => ['nullable', 'email', 'max:255'],

            'status' => [
                'sometimes',
                'string',
                Rule::in(['active', 'inactive', 'suspended']),
            ],
        ];
    }
}
