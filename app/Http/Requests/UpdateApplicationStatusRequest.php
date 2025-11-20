<?php

namespace App\Http\Requests;

use App\Enums\ApplicationStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateApplicationStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'string', Rule::in(ApplicationStatus::values())],
        ];
    }

    public function messages(): array
    {
        return [
            'status.in' => 'Status must be one of: ' . implode(', ', ApplicationStatus::values()),
        ];
    }
}
