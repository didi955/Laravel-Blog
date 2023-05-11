<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Utilities\RequestUtilities;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SessionsController extends Controller
{

    public function create(): View
    {
        return view('sessions.create');
    }

    /**
     * @throws ValidationException
     */
    public function store(): RedirectResponse
    {
        $attributes = request()->validate([
            'email' => ['required', 'email', Rule::exists('users', 'email')],
            'password' => ['required', 'min:8', 'max:255', Rules\Password::defaults()],
            'remember' => ['nullable'],
        ]);

        $remember = RequestUtilities::convertCheckboxValueToBoolean($attributes['remember'] ?? null);

        if (!Auth::attempt($attributes, $remember)) {
            throw ValidationException::withMessages([
                'email' => 'Your provided credentials could not be verified.'
            ]);
        }

        session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME)->with('success', 'Welcome Back!');
    }

    public function destroy(Request $request): RedirectResponse
    {
        auth()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Goodbye!');
    }

    public function profile(): View
    {
        return view('profile');
    }


}
