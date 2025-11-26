<?php

namespace App\Http\Requests\Employer;

use Illuminate\Foundation\Http\FormRequest;

class CompanyOnboardingRequest extends FormRequest
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
          
            'logo' =>  'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name' => ['nullable'],
            'description'  => ['nullable', 'max:2000'],
            'industry' => ['nullable'],
            'company_size'  => ['nullable'],
            'website_url'  => ['nullable', 'active_url'],
            'state'  => ['nullable', 'string'],
            'country'  => ['nullable', 'string'],

        ];
    }
}
