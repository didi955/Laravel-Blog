<?php

declare(strict_types=1);

namespace App\Services;

use App\Utilities\RequestUtilities;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SessionService
{
    public function login(array $attributes): void
    {
        $remember = RequestUtilities::convertCheckboxValueToBoolean($attributes['remember'] ?? null);

        if ( ! auth()->attempt($attributes, $remember)) {
            throw ValidationException::withMessages([
                'password' => 'Your provided credentials could not be verified.',
            ]);
        }

        session()->regenerate();
    }

    public function logout(Request $request): void
    {
        auth()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();
    }
}
