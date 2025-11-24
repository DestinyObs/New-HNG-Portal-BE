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
            'email' => 'required|email',
            'role' => ['nullable', Rule::in(['talent', 'company'])],
            'name' => 'nullable|string|max:255',
            'company_name' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Email is required.',
            'email.email' => 'Please provide a valid email.',
            'role.in' => 'Role must be either talent or company.',
            'name.string' => 'First name must be a valid string.',
            'name.max' => 'First name cannot exceed 255 characters.',
            'company_name.string' => 'Company name must be a valid string.',
            'company_name.max' => 'Company name cannot exceed 255 characters.',
        ];
    }
}
