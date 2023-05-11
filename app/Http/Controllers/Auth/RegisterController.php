<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Utilities\Role;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function create(): View
    {
        return view('register.create');
    }

    public function store()
    {
        $attributes = request()->validate([
            'lastname' => ['required', 'string', 'min:3', 'max:255'],
            'firstname' => ['required', 'string', 'min:3', 'max:255'],
            'username' => ['required', 'string', 'min:3', 'max:255', 'alpha_dash', Rule::unique('users', 'username')],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', 'min:8', 'max:255', 'confirmed', Rules\Password::defaults()]
        ]);

        $attributes['role'] = Role::MEMBER;

        $user = User::create($attributes);

        $user->sendEmailVerificationNotification();

        auth()->login($user);

        return redirect('/')->with('success', 'Your account has been created, please verify your email address.');

    }
}
