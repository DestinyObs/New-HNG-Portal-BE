<?php

namespace App\Http\Requests\Employer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateJobRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // echo 'reached here';
        // die;
        $companyId = $this->route('companyId');

        return [
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

            // Basic fields
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'acceptance_criteria' => 'sometimes|string',

            // Price
            'price' => 'sometimes|numeric',

            // Location fields
            'state_id' => 'sometimes|uuid|exists:states,id',
            'country_id' => 'sometimes|uuid|exists:countries,id',

            // Relation fields
            'track_id' => 'sometimes|uuid|exists:tracks,id',
            'category_id' => 'sometimes|uuid|exists:categories,id',
            'job_type_id' => 'sometimes|uuid|exists:job_types,id',

            // Work mode if you have it on your table
            'work_mode_id' => 'sometimes|uuid|exists:work_modes,id',

            // Skills
            'skills' => 'sometimes|array',
            'skills.*' => 'uuid|exists:skills,id',

            // For updating a draft job
            // 'job_id'               => 'sometimes|uuid|exists:job_listings,id',
        ];
    }

    public function messages(): array
    {
        return [
            'title.string' => 'The job title must be a valid string.',
            'description.string' => 'The job description must be text.',

            'state_id.exists' => 'Invalid state selected.',
            'country_id.exists' => 'Invalid country selected.',
            'track_id.exists' => 'Invalid track selected.',
            'category_id.exists' => 'Invalid category selected.',
            'job_type_id.exists' => 'Invalid job type selected.',
            'work_mode_id.exists' => 'Invalid work mode selected.',

            'skills.array' => 'Skills must be an array.',
            'skills.*.exists' => 'One or more skills are invalid.',

            // 'job_id.exists' => 'The referenced job does not exist.',
        ];
    }
}
