<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Storage;

class UserService
{
    public function update(array $attributes): void
    {
        $user = auth()->user();

        if($attributes['avatar'] ?? false) {
            $this->deleteAvatar($user);
            $attributes['avatar'] = $this->storeAvatar();
        }

        $user->update($attributes);
        $this->emailWasChanged($user);
    }

    private function storeAvatar(): false|string
    {
        return request()->file('avatar')
            ->store('avatars', 'public');

    }

    private function deleteAvatar(User $user): void
    {
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }
    }

    private function emailWasChanged(User $user): void
    {
        // invalidate and send another email verification notification if email has changed
        if ($user->wasChanged('email')) {
            $user->email_verified_at = null;
            $user->sendEmailVerificationNotification();
        }
    }
}
