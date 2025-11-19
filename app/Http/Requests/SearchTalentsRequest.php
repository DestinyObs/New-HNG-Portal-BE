<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchTalentsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'skills' => ['sometimes', 'array'],
            'skills.*' => ['string', 'exists:skills,id'],
            'track_id' => ['sometimes', 'uuid', 'exists:tracks,id'],
            'min_salary' => ['sometimes', 'integer', 'min:0'],
            'max_salary' => ['sometimes', 'integer', 'min:0', 'gte:min_salary'],
            'is_verified' => ['sometimes', 'boolean'],
            'location_id' => ['sometimes', 'uuid', 'exists:locations,id'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
            'search' => ['sometimes', 'string', 'max:255'],
        ];
    }
}
