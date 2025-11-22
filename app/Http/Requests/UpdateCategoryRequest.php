<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Http\Controllers\Concerns\ApiResponse;

class UpdateCategoryRequest extends FormRequest
{
    use ApiResponse;

    public function rules(): array
    {
        return [
            'name' => 'required|string|unique:categories,name,' . $this->id
        ];
    }

    /**
     * Override failed validation to use ApiResponse.
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->unprocessable(
                'Validation failed',
                $validator->errors()->toArray()
            )
        );
    }
}
