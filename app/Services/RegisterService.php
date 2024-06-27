<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Utilities\Role;

class RegisterService
{
    public function register(array $attributes): void
    {
        $attributes['role'] = Role::MEMBER;

        $user = User::create($attributes);

        $user->sendEmailVerificationNotification();

        auth()->login($user);
    }
}
