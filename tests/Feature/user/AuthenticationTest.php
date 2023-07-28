<?php

declare(strict_types=1);

namespace Tests\Feature\user;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Database\Factories\UserFactory;

beforeEach(function (): void {
    $this->user = User::factory()->create();
});

it('login page can be rendered', function (): void {
    $response = $this->get(route('login'));

    $response->assertOk();
});

it('user can login with correct credentials', function (): void {
    $response = $this->post(route('login'), [
        'email' => $this->user->email,
        'password' => UserFactory::$PASSWORD_EXAMPLE,
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(RouteServiceProvider::HOME);
});

it('user can not login with invalid email', function (): void {
    $response = $this->post(route('login'), [
        'email' => 'badEmail',
        'password' => UserFactory::$PASSWORD_EXAMPLE,
    ]);

    $response->assertInvalid([
        'email' => 'The email field must be a valid email address.',
    ]);

    $this->assertGuest();

    $response = $this->post(route('login'), [
        'email' => 'dylan@gmail.com',
        'password' => UserFactory::$PASSWORD_EXAMPLE,
    ]);

    $response->assertInvalid([
        'email' => 'The selected email is invalid.',
    ]);
});

it('user can not login with invalid credentials', function (): void {

    $response = $this->post(route('login'), [
        'email' => $this->user->email,
        'password' => fake()->password(8)
    ]);

    $response->assertInvalid(['password' => 'Your provided credentials could not be verified.']);

    $this->assertGuest();
});
