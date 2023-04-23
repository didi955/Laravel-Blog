<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index()
    {
        return view('profile');
    }

    public function update()
    {

        $attributes = request()->validate([
            'lastname' => ['required', 'min:3', 'max:255'],
            'firstname' => ['required', 'min:3', 'max:255'],
            'username' => ['required', 'min:3', 'max:255', 'alpha_dash', Rule::unique('users', 'username')->ignore(auth()->user())],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore(auth()->user())],
            'password' => ['nullable', 'min:8', 'max:255', 'confirmed', Rules\Password::defaults()],
            'avatar' => ['nullable', 'image', 'max:1024'],
        ]);

        try {
            $user = auth()->user();
            if(array_key_exists('avatar', $attributes) && $attributes['avatar'] != null){
                $attributes['avatar'] = request()->file('avatar')->store('avatars', 'public');
                if($user->avatar != null){
                    Storage::disk('public')->delete($user->avatar);
                }
            }
            $user->update($attributes);
        }
        catch(\Exception $e){
            return back()->with('error', 'An error occured while updating your profile');
        }

        // send email verification if email has changed
        if($user->wasChanged('email'))
            $user->sendEmailVerificationNotification();


        return back()->with('success', 'Profile updated successfully');
    }
}
