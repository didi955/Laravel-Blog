<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Utilities\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    public static string $PASSWORD_EXAMPLE = 'gJ1323L!Ah&@tp%Ade';

    protected $model = \App\Models\User::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'lastname' => fake()->lastName(),
            'firstname' => fake()->firstName(),
            'username' => fake()->unique()->userName(),
            'avatar' => null,
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'role' => Role::MEMBER->value,
            'password' => Hash::make(self::$PASSWORD_EXAMPLE),
            'remember_token' => Str::random(10),
        ];
    }
}
