<?php

declare(strict_types=1);

namespace Tests\Feature\user;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Database\Factories\UserFactory;

it('registration page can be rendered', function (): void {
    $response = $this->get(route('register'));

    $response->assertOk();
});

it('user can register with correct credentials', function (): void {
    $response = $this->post(route('register'), [
        'lastname'              => 'Lannuzel',
        'firstname'             => 'Dylan',
        'username'              => 'didi',
        'email'                 => 'dylan@gmail.com',
        'password'              => UserFactory::$PASSWORD_EXAMPLE,
        'password_confirmation' => UserFactory::$PASSWORD_EXAMPLE,
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(RouteServiceProvider::HOME);
});

it('user can not register with invalid email', function (): void {
    $response = $this->post(route('register'), [
        'lastname'              => 'Lannuzel',
        'firstname'             => 'Dylan',
        'username'              => 'didi',
        'email'                 => 'dygmailcom',
        'password'              => 'badPassword',
        'password_confirmation' => 'badPassword',
    ]);

    $response->assertInvalid([
        'email',
    ]);

    $this->assertGuest();
});

it('user can not register with invalid password confirmation', function (): void {
    $response = $this->post(route('register'), [
        'lastname'              => 'Lannuzel',
        'firstname'             => 'Dylan',
        'username'              => 'didi',
        'email'                 => 'dylan@gmail.com',
        'password'              => 'badPassword85*',
        'password_confirmation' => 'badPassword86$',
    ]);

    $response->assertInvalid([
        'password',
    ]);

    $this->assertGuest();
});

it('user can not register with invalid names', function (): void {
    $response = $this->post(route('register'), [
        'lastname'              => 'Lannuzel85',
        'firstname'             => 'Dylan4',
        'username'              => 'didi',
        'email'                 => 'dylan@gmail.com',
        'password'              => 'badPassword',
        'password_confirmation' => 'badPassword',
    ]);

    $response->assertInvalid([
        'lastname',
        'firstname',
    ]);

    $this->assertGuest();
});

it('user can not register with already existing email', function (): void {

    $email = 'dylan@gmail.com';

    User::factory()->create([
        'email' => $email
    ]);

    $response = $this->post(route('register'), [
        'lastname'              => 'Lannuzel',
        'firstname'             => 'Dylan',
        'username'              => 'didi',
        'email'                 => $email,
        'password'              => 'badPassword',
        'password_confirmation' => 'badPassword',
    ]);

    $response->assertInvalid([
        'email'
    ]);

    $this->assertGuest();
});
