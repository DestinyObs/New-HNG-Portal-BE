<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class GoogleAuthRequest extends FormRequest
{
    public function authorize()
    {
        return true; // allow guests
    }

    public function rules()
    {
        return [
            'token' => 'required|string',
        ];
    }
}
