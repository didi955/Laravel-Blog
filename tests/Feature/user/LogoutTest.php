<?php

declare(strict_types=1);

namespace Tests\Feature\user;

use App\Models\User;
use App\Providers\RouteServiceProvider;

it('user can logout', function (): void {

    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('logout'))
        ->assertRedirect(RouteServiceProvider::HOME);

    $this->assertGuest();
});
