<?php

declare(strict_types=1);

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Http\UploadedFile;

it('can show his profile page', function (): void {

    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('profile'))
        ->assertOk();
});

it('can update his profile', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->patch(route('profile'), [
            'lastname'  => 'Doe',
            'firstname' => 'John',
            'email'      => 'john.doe@gamil.com',
            'username'   => 'john_doe'
        ])
        ->assertRedirect();

    $user->refresh();

    expect($user->lastname)->toBe('Doe')
        ->and($user->firstname)->toBe('John')
        ->and($user->email)->toBe('john.doe@gamil.com')
        ->and($user->username)->toBe('john_doe');
});

it('can upload his avatar', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->patch(route('profile'), [
            'lastname'  => 'Doe',
            'firstname' => 'John',
            'email'      => 'john.doe@gamil.com',
            'username'   => 'john_doe',
            'avatar' => $file = UploadedFile::fake()->image('avatar.jpg')
        ])
        ->assertRedirect();

    $user->refresh();

    expect($user->avatar)->toBe('avatars/' . $file->hashName());
});

it('can update his avatar', function (): void {
    $user = User::factory()->create(
        [
            'avatar' => 'avatars/avatar.jpg'
        ]
    );

    $this->actingAs($user)
        ->patch(route('profile'), [
            'lastname'  => 'Doe',
            'firstname' => 'John',
            'email'      => 'john.doe@gamil.com',
            'username'   => 'john_doe',
            'avatar' => $file = UploadedFile::fake()->image('avatar.jpg')
        ])
        ->assertRedirect();

    $user->refresh();

    expect($user->avatar)->toBe('avatars/' . $file->hashName());
});

it('cant change because passwords doesnt match', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->patch(route('profile'), [
            'lastname'              => 'Doe',
            'firstname'             => 'John',
            'email'                 => 'john.doe@gamil.com',
            'username'              => 'john_doe',
            'password'              => 'badPassword85*',
            'password_confirmation' => 'badPassword85$'
        ])
        ->assertInvalid(['password']);

});
