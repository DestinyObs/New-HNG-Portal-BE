<?php

namespace App\Http\Requests\Talent;

use Illuminate\Foundation\Http\FormRequest;

class TalentOnboardingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'current_role'  => ['required'],
            'bio' => ['required'],
            'track_id' => ['required', 'exists:tracks,id'],
            'project_name'  => ['required'],
            'project_url'  => ['required'],
            'project_file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|pdf|docx|max:2048',
        ];
    }
}
