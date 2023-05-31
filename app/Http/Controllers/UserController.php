<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        return view('profile.index');
    }

    public function update(): RedirectResponse
    {
        $attributes = request()->validate([
            'lastname'  => ['required', 'min:3', 'max:255'],
            'firstname' => ['required', 'min:3', 'max:255'],
            'username'  => ['required', 'min:3', 'max:255', 'alpha_dash', Rule::unique('users', 'username')->ignore(auth()->user())],
            'email'     => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore(auth()->user())],
            'password'  => ['nullable', 'min:8', 'max:255', 'confirmed', Rules\Password::defaults()],
            'avatar'    => ['nullable', 'image', 'max:1024'],
        ]);

        try {
            $user = auth()->user();
            if (array_key_exists('avatar', $attributes) && $attributes['avatar'] != null) {
                $attributes['avatar'] = request()->file('avatar')->store('avatars', 'public');
                if ($user->avatar != null) {
                    Storage::disk('public')->delete($user->avatar);
                }
            }
            $user->update($attributes);
        } catch(\Exception) {
            return back()->with('error', 'An error occured while updating your profile');
        }

        // invalidate and send another email verification notification if email has changed
        if ($user->wasChanged('email')) {
            $user->email_verified_at = null;
            $user->sendEmailVerificationNotification();
        }

        return back()->with('success', 'Profile updated successfully');
    }
}
