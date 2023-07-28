<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return ! auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array>
     */
    public function rules(): array
    {
        return [
            'lastname' => ['required', 'string', 'alpha', 'min:3', 'max:255'],
            'firstname' => ['required', 'string', 'alpha', 'min:3', 'max:255'],
            'username' => ['required', 'string', 'min:3', 'max:255', 'alpha_dash', Rule::unique('users', 'username')],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => [Password::min(8)->letters()->numbers()->mixedCase()->symbols()->required(), 'max:255', 'confirmed',]
        ];
    }
}
