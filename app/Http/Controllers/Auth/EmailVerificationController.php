<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\View\View;

class EmailVerificationController extends Controller
{
    public function create(): View
    {
        return view('sessions.verify-email');
    }

    public function verify(EmailVerificationRequest $request)
    {
        $request->fulfill();

        return redirect('/')->with('success', 'Your email address has been verified.');
    }

    public function resend()
    {
        if (request()->user()->hasVerifiedEmail()) {
            return redirect('/');
        }

        request()->user()->sendEmailVerificationNotification();

        return back()->with('success', 'Email verification link sent!');
    }
}
