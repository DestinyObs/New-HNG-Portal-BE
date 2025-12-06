<?php

namespace App\Http\Requests\Employer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreDraftJobRequest extends FormRequest
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
        $companyId = $this->route('companyId');
        // dd($companyId);

        return [
            'title' => 'required|string|max:255',
            'company' => [
                Rule::exists('companies', 'id')
                    ->where('user_id', Auth::id()),
            ],
            'job_id' => [
                'sometimes',
                'string',
                Rule::exists('job_listings', 'id')
                    ->where('company_id', $companyId),
            ],
            'description' => 'nullable|string',
            'acceptance_criteria' => 'nullable|string',
            'country' => 'nullable|string',
            'state' => 'nullable|string',
            'price' => ['nullable', 'numeric', 'regex:/^\d{1,10}(\.\d{1,2})?$/'],
            'track_id' => 'nullable|uuid|exists:tracks,id',
            'category_id' => 'nullable|uuid|exists:categories,id',
            'job_type_id' => 'nullable|uuid|exists:job_types,id',
            'work_mode_id' => 'nullable|uuid|exists:work_modes,id',
            'job_level_id' => 'nullable|uuid|exists:job_levels,id',
            'skills' => 'nullable|array',
            'skills.*' => 'uuid|exists:skills,id',
            'status' => 'nullable|in:draft',
            // 'publication_status' => 'nullable|in:published,unpublished',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'The job title is required.',
            'job_id.exists' => 'The specified job does not exist or you do not have permission to edit it.',
            'description.required' => 'The job description is required.',
            'track_id.exists' => 'The selected track is invalid.',
            'category_id.exists' => 'The selected category is invalid.',
            'job_type_id.exists' => 'The selected job type is invalid.',
            'job_level_id' => 'The selected job level is invalid.',
            'work_mode_id.exists' => 'The selected work mode is invalid.',
            'skills.array' => 'The skills must be an array of skill IDs.',
            'skills.*.exists' => 'One or more selected skills are invalid.',
            'status.in' => 'The status must be set as draft.',
            'price.numeric'  => 'Salary must be a valid number.',
            'price.regex'    => 'Salary must not exceed 10 digits and can only have up to 2 decimal places.',
            // 'publication_status.in' => 'The publication status must be either published',
        ];
    }
}