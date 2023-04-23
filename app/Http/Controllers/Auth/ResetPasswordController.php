<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;

class ResetPasswordController extends Controller
{

    public function create($token)
    {
        return view('sessions.reset-password', ['token' => $token]);
    }

    public function resetPassword()
    {
        request()->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'min:8', 'max:255', 'confirmed', Rules\Password::defaults()],
        ]);

        $status = Password::reset(
            request()->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => $password,
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status == Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success', __($status))
            : back()->withInput(request()->only('email'))
                ->withErrors(['email' => __($status)]);
    }
}
