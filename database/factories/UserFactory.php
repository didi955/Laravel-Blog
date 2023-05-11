<?php

namespace Database\Factories;

use App\Utilities\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{

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
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];
    }
}
