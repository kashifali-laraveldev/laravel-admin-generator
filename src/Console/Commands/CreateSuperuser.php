<?php

namespace Bitsoftsol\LaravelAdministration\Console\Commands;

use Illuminate\Console\Command;
use Bitsoftsol\LaravelAdministration\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateSuperuser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'createsuperuser';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $username = $this->ask('Username:');
        if (User::where('username', $username)->exists()) {
            $this->error('Username already exists.');
            return;
        }
        $email = $this->ask('Email Address:');
        if (User::where('email', $email)->exists()) {
            $this->error('Email already exists.');
            return;
        }
        $password = $this->secret('Password:');
        $confirmPassword = $this->secret('Confirm Password:');

        $validator = Validator::make([
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'confirm_password' => $confirmPassword,
        ], [
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'confirm_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            $this->error($validator->errors()->first());
            return;
        }

        $user = User::create([
            'first_name' => '',
            'last_name' => '',
            'name' => $username,
            'email' => $email,
            'username' => $username,
            'is_superuser' => true,
            'last_login' => Carbon::now(),
            'date_joined' => Carbon::now(),
            'is_active' => true,
            'is_staff' => true,
            'password' => Hash::make($password),
        ]);

        $this->info('Superuser created successfully.');

    }
}
