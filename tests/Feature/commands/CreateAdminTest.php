<?php

declare(strict_types=1);

namespace Tests\Feature\Commands;

use App\Console\Commands\CreateAdmin;

it('creates an admin', function (): void {
    $this->artisan(CreateAdmin::class)
        ->expectsQuestion('What first name do you want?', 'John')
        ->expectsQuestion('What last name?', 'Doe')
        ->expectsQuestion('What username?', 'john_doe')
        ->expectsQuestion('What email?', 'john.doe@gmail.com')
        ->expectsQuestion('What role should the user have?', 'Admin')
        ->expectsQuestion('What password?', 'badPassword85*')
        ->expectsQuestion('Confirm password', 'badPassword85*')
        ->expectsOutput('User created!')
        ->assertSuccessful();
});

it('does not create an admin if passwords do not match', function (): void {
    $this->artisan(CreateAdmin::class)
        ->expectsQuestion('What first name do you want?', 'John')
        ->expectsQuestion('What last name?', 'Doe')
        ->expectsQuestion('What username?', 'john_doe')
        ->expectsQuestion('What email?', 'john.doe@gmail.com')
        ->expectsQuestion('What role should the user have?', 'Admin')
        ->expectsQuestion('What password?', 'badPassword85*')
        ->expectsQuestion('Confirm password', 'badPassword4$')
        ->expectsOutput('Passwords do not match!')
        ->assertExitCode(1);
});
