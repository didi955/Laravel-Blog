<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\User;
use App\Utilities\Role;
use Illuminate\Console\Command;

use function Laravel\Prompts\password;
use function Laravel\Prompts\select;
use function Laravel\Prompts\text;

final class CreateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create admin user';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->comment('Creating User...');
        $firstname = text(
            label: 'What first name do you want?',
            placeholder: 'E.g. Dylan',
            required: true,
        );
        $lastname = text(
            label: 'What last name?',
            placeholder: 'E.g. Lannuzel',
            required: true,
        );
        $username = text(
            label: 'What username?',
            placeholder: 'E.g. DiDi',
            required: true,
            validate: fn (string $value) => match(true) {
                User::where('username', $value)->exists() => 'The username is already taken.',
                default => null,
            }
        );
        $email = text(
            label: 'What email?',
            required: true,
            validate: fn (string $value) => match(true) {
                false === filter_var($value, FILTER_VALIDATE_EMAIL) => 'The email must be valid.',
                User::where('email', $value)->exists() => 'The email is already taken.',
                default => null,
            }
        );
        $role = select(
            label: 'What role should the user have?',
            options: Role::toArray(),
            default: Role::ADMIN->value,
        );
        $password = password(
            label: 'What password?',
            required: true,
        );
        $confirmPassword = password(
            label: 'Confirm password',
            required: true,
        );
        if ($password !== $confirmPassword) {
            $this->error('Passwords do not match!');

            return 1;
        }

        $admin = new User();
        $admin->firstname = $firstname;
        $admin->lastname = $lastname;
        $admin->username = $username;
        $admin->email = $email;
        $admin->role = $role;
        $admin->password = $password;
        $admin->save();

        $this->info('User created!');
        $admin->sendEmailVerificationNotification();

        return 0;
    }
}
