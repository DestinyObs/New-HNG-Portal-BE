<?php

namespace App\Http\Requests\Talent;

use Illuminate\Foundation\Http\FormRequest;

class WorkExperienceProfileSettingRequest extends FormRequest
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
            'job_title' => ['nullable', 'string'],
            'company_name' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'start_date' => ['nullable', 'date', 'before:end_date'],
            'end_date' => ['nullable', 'date', 'after:start_date']
        ];
    }
}
