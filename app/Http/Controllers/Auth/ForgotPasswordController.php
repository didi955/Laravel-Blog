<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{

    public function create()
    {
        return view('sessions.forgot-password');
    }

    public function forgotPassword()
    {
        request()->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            request()->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('success', 'Password reset link sent to your email.')
            : back()->withErrors(['email' => [__($status)]]);

    }
}
