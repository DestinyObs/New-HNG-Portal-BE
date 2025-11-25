namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GoogleAuthRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'google_token' => 'required|string',
            'role' => ['nullable', Rule::in(['talent', 'company'])],
            'company_name' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'google_token.required' => 'Google token is required.',
            'google_token.string' => 'Google token must be a valid string.',
            'role.in' => 'Role must be either talent or company.',
            'company_name.string' => 'Company name must be a valid string.',
            'company_name.max' => 'Company name cannot exceed 255 characters.',
        ];
    }
}
