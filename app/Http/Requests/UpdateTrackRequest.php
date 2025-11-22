<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Controllers\Concerns\ApiResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateTrackRequest extends FormRequest
{
    use ApiResponse;

    public function rules(): array
    {
        return [
            'name' => 'required|string|unique:tracks,name,' . $this->id
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->unprocessable(
            'Validation failed',
            $validator->errors()->toArray()
        ));
    }
}
