<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class SessionsController extends Controller
{

    public function create()
    {
        return view('sessions.create');
    }

    /**
     * @throws ValidationException
     */
    public function store()
    {
        $attributes = request()->validate([
            'email' => ['required', 'email', Rule::exists('users', 'email')],
            'password' => ['required', 'min:8', 'max:255', Rules\Password::defaults()],
            'remember' => ['boolean', 'nullable'],
        ]);

        $remember = (bool)request('remember');

        if (!auth()->attempt($attributes, $remember)) {
            throw ValidationException::withMessages([
                'email' => 'Your provided credentials could not be verified.'
            ]);
        }

        session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME)->with('success', 'Welcome Back!');
    }

    public function destroy(Request $request) : RedirectResponse
    {
        auth()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Goodbye!');
    }

    public function profile()
    {
        return view('profile');
    }


}
