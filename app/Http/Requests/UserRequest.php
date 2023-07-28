<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return null !== $this->user();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'lastname' => ['required', 'alpha', 'min:3', 'max:255'],
            'firstname' => ['required', 'alpha', 'min:3', 'max:255'],
            'username' => [
                'required', 'min:3', 'max:255', 'alpha_dash',
                Rule::unique('users', 'username')->ignore(auth()->user()),
            ],
            'email' => [
                'required', 'email', 'max:255', Rule::unique('users', 'email')
                    ->ignore(auth()->user()),
            ],
            'password' => [
                'nullable', 'max:255', 'confirmed',
                Password::min(8)->letters()->numbers()->mixedCase()->symbols(),
            ],
            'avatar' => ['nullable', 'image', 'max:1024'],
        ];
    }
}
