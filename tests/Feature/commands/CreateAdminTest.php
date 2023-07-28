<?php

declare(strict_types=1);

namespace Tests\Feature\Commands;

use App\Console\Commands\CreateAdmin;
use App\Models\User;

it('creates an admin', function (): void {
    $this->artisan(CreateAdmin::class)
        ->expectsQuestion('Firstname', 'John')
        ->expectsQuestion('Lastname', 'Doe')
        ->expectsQuestion('Username', 'john_doe')
        ->expectsQuestion('Email', 'john.doe@gmail.com')
        ->expectsQuestion('Password', 'badPassword85*')
        ->expectsQuestion('Confirm password', 'badPassword85*')
        ->expectsOutput('Admin user created!')
        ->assertSuccessful();
});

it('does not create an admin if passwords do not match', function (): void {
    $this->artisan(CreateAdmin::class)
        ->expectsQuestion('Firstname', 'John')
        ->expectsQuestion('Lastname', 'Doe')
        ->expectsQuestion('Username', 'john_doe')
        ->expectsQuestion('Email', 'john.doe@gmail.com')
        ->expectsQuestion('Password', 'badPassword85*')
        ->expectsQuestion('Confirm password', 'badPassword4$')
        ->expectsOutput('Passwords do not match!')
        ->assertExitCode(1);
});

it('does not create an admin if user already exists', function (): void {

    User::factory()->create([
        'username' => 'john_doe',
        'email' => 'john.doe@gmail.com',
    ]);

    $this->artisan(CreateAdmin::class)
        ->expectsQuestion('Firstname', 'John')
        ->expectsQuestion('Lastname', 'Doe')
        ->expectsQuestion('Username', 'john_doe')
        ->expectsQuestion('Email', 'john.doe@gmail.com')
        ->expectsQuestion('Password', 'badPassword85*')
        ->expectsQuestion('Confirm password', 'badPassword85*')
        ->expectsOutput('User already exists!')
        ->assertExitCode(1);
});
