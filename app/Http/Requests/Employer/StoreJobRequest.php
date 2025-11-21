<?php

namespace App\Http\Requests\Employer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreJobRequest extends FormRequest
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

        return [
            'company' => [
                Rule::exists('companies', 'id')
                    ->where('user_id', Auth::id())
            ],
            'job_id' => [
                'sometimes',
                'string',
                Rule::exists('job_listings', 'id')
                    ->where('company_id', $companyId),
            ],
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'acceptance_criteria' => 'required|string',
            'country_id' => 'required|uuid|exists:countries,id',
            'state_id' => 'required|uuid|exists:states,id',
            'price' => 'required|numeric',
            'track_id' => 'required|uuid|exists:tracks,id',
            'category_id' => 'required|uuid|exists:categories,id',
            'job_type_id' => 'required|uuid|exists:job_types,id',
            'work_mode_id' => 'required|uuid|exists:work_modes,id',
            'skills' => 'required|array',
            'skills.*' => 'uuid|exists:skills,id',
            // 'status' => 'required|in:active,in-active',
            // 'publication_status' => 'required|in:published',
        ];
    }


    public function messages(): array
    {
        return [
            'company.exists' => 'You do not have permission to manage this company.',
            'job_id.exists' => 'The specified job does not exist or you do not have permission to edit it.',
            'title.required' => 'The job title is required.',
            'description.required' => 'The job description is required.',
            'country_id.required' => "Country field is required",
            'country_id.exists' => 'The selected country is invalid.',
            'state_id.required' => "State field is required.",
            'state_id.exists' => 'The selected state is invalid.',
            'track_id.required' => "Track field is required.",
            'track_id.exists' => 'The selected track is invalid.',
            'category_id.required' => "Category field is required.",
            'category_id.exists' => 'The selected category is invalid.',
            'job_type_id.required' => "Job type field is required.",
            'job_type_id.exists' => 'The selected job type is invalid.',
            'work_mode_id.required' => "Work mode field is required.",
            'work_mode_id.exists' => 'The selected work mode is invalid.',
            'skills.required' => "Atleast one skill must be added.",
            'skills.array' => 'The skills must be an array of skill IDs.',
            'skills.*.exists' => 'One or more selected skills are invalid.',
            // 'status.required' => "Status must be added",
            // 'status.in' => 'The status must be set as draft.',
            // 'publication_status.required' => "Publication status must be entered",
            // 'publication_status.in' => 'The publication status must be set to published',
        ];
    }
}