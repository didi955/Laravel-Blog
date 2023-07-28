<?php

declare(strict_types=1);

namespace Tests\Feature\events;

use App\Listeners\User\PasswordResetListener;
use App\Models\User;
use App\Notifications\User\PasswordResetInformation;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;

it('is attached with PasswordResetListener listener', function (): void {

    Event::fake();
    Event::assertListening(PasswordReset::class, PasswordResetListener::class);
});

it('is send a notification when a password is reset', function (): void {

    Notification::fake();

    $user = User::factory()->create();

    $event = new PasswordReset($user);
    $listener = new PasswordResetListener();
    $listener->handle($event);

    Notification::assertSentTo($user, PasswordResetInformation::class);

});
