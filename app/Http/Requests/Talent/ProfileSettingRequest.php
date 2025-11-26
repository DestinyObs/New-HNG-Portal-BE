<?php

namespace App\Http\Requests\Talent;

use Illuminate\Foundation\Http\FormRequest;

class ProfileSettingRequest extends FormRequest
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
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'current_role'  => ['nullable'],

            'bio' => ['nullable'],
            
            'experience'  => ['nullable'], // 2-4 years
            'available_status'  => ['nullable'], // available for work | open to offers
            'job_type_preference'  => ['nullable', 'in:remote,hybrid,onsite'], // job_type_preference remote | hybrid | onsite
            
            'state' => ['nullable'],
            'country' => ['nullable'],  
        ];
    }
}
