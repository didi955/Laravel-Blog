<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class ResetPasswordController extends Controller
{
    public function create(string $token): View
    {
        return view('sessions.reset-password', ['token' => $token]);
    }

    public function resetPassword(): RedirectResponse
    {
        request()->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => [Rules\Password::min(8)->letters()->numbers()->mixedCase()->symbols()->required(), 'max:255', 'confirmed']
        ]);

        $status = Password::reset(
            request()->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password): void {
                $user->forceFill([
                    'password' => $password,
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return Password::PASSWORD_RESET === $status
            ? redirect()->route('login')->with('success', __($status))
            : back()->withInput(request()->only('email'))
                ->withErrors(['email' => __($status)]);
    }
}
