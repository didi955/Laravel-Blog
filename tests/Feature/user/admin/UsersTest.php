<?php

declare(strict_types=1);

namespace Tests\Feature\User;

use App\Models\User;
use App\Utilities\Role;

it('can view users', function (): void {
    $user = User::factory()->create(
        [
            'role' => Role::ADMIN->value,
            'email_verified_at' => now(),
        ]
    );

    $this->actingAs($user)
        ->get(route('admin.users.index'))
        ->assertOk();
});

it('can not view users', function (): void {
    $user = User::factory()->create(
        [
            'role' => Role::MEMBER->value,
            'email_verified_at' => now(),
        ]
    );

    $this->actingAs($user)
        ->get(route('admin.users.index'))
        ->assertForbidden();


});
