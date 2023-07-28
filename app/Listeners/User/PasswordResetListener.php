<?php

declare(strict_types=1);

namespace App\Listeners\User;

use App\Notifications\User\PasswordResetInformation;
use Illuminate\Auth\Events\PasswordReset;

class PasswordResetListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     */
    public function handle(PasswordReset $event): void
    {
        $event->user->notify(new PasswordResetInformation());
    }
}
