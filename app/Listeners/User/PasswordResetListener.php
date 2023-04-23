<?php

namespace App\Listeners\User;

use App\Notifications\User\PasswordResetInformation;

class PasswordResetListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        $event->user->notify(new PasswordResetInformation());
    }
}
