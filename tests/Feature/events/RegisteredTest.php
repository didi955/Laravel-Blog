<?php

declare(strict_types=1);

namespace Tests\Feature\events;

use App\Models\User;
use App\Notifications\User\VerifyEmailQueued;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;

it('is attached with SendEmailVerificationNotification listener', function (): void {

    Event::fake();
    Event::assertListening(Registered::class, SendEmailVerificationNotification::class);
});

it('is send a notification when a user is registered', function (): void {

    Notification::fake();

    $user = User::factory()->make(
        [
            'email_verified_at' => null,
        ]
    );

    $event = new Registered($user);
    $listener = new SendEmailVerificationNotification();
    $listener->handle($event);

    Notification::assertSentTo($user, VerifyEmailQueued::class);

});
