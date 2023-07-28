<?php

declare(strict_types=1);

namespace App\Notifications\User;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class VerifyEmailQueued extends VerifyEmail implements ShouldQueue
{
    use Queueable;

    public function __construct()
    {
        $this->onQueue('notifications');
    }
}
