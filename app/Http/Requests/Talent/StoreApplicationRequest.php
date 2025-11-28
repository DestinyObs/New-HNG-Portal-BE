<?php

namespace App\Http\Requests\Talent;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'job_id' => [
                'required',
                'string',
                Rule::exists('job_listings', 'id'),
            ],

            // Portfolio link must be a valid URL
            'portfolio_url' => [
                'nullable',
                'url',
                'max:500',
            ],

            // PDF only — max 5MB
            'resume' => [
                'required', // make it compulsory
                'file',
                'mimes:pdf',
                'max:5120', // 5MB
            ],

            // Cover letter — minimum 30 words, max 300 words
            'cover_letter' => [
                'required',
                'string',
                'min:50',
                'max:2000',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'job_id.required' => 'Job ID is required.',
            'job_id.exists'   => 'The selected job is invalid.',

            'portfolio_url.url' => 'Your portfolio link must be a valid URL.',
            'portfolio_url.max' => 'Portfolio link cannot exceed :max characters.',

            'resume.required' => 'Please upload your resume or PDF document.',
            'resume.mimes'    => 'Only PDF files are allowed.',
            'resume.max'      => 'The resume must not exceed 5MB.',

            'cover_letter.required' => 'A cover letter is required.',
            'cover_letter.min'      => 'Your cover letter must be at least :min characters.',
            'cover_letter.max'      => 'Your cover letter cannot exceed :max characters.',
        ];
    }
}
