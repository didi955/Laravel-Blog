<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

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
        $this->comment('Creating admin user...');
        $firstname = $this->ask('Firstname');
        $lastname = $this->ask('Lastname');
        $username = $this->ask('Username');
        $email = $this->ask('Email');
        $password = $this->secret('Password');
        $confirmPassword = $this->secret('Confirm password');
        if ($password !== $confirmPassword) {
            $this->error('Passwords do not match!');

            return 1;
        }

        // Verify if username and email do not already exist
        if (User::where('username', $username)->orWhere('email', $email)->exists()) {
            $this->error('User already exists!');

            return 1;
        }

        $admin = new User();
        $admin->firstname = $firstname;
        $admin->lastname = $lastname;
        $admin->username = $username;
        $admin->email = $email;
        $admin->password = $password;
        $admin->save();

        $this->info('Admin user created!');
        $admin->sendEmailVerificationNotification();

        return 0;
    }
}
