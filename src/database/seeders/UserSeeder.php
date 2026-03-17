<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('users')->truncate();
        DB::table('users')->insert([
            [
                // Latitude and longitude => Devstudio
                'id' => 1,
                'name' => 'Bit Sof',
                'username' => 'bit-soft-admin',
                'email' => 'admin@bitsoft.com',
                'password' => Hash::make('bitsoft123'),
                'email_verified_at' => Carbon::now(),
                'last_login' => Carbon::now(),
                'is_superuser' => 1,
                'first_name' => "Bitsoft",
                'last_name' => "Admin",
                'is_staff' => 0,
                'is_active' => 1,
                'date_joined' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                // Latitude and longitude => Devstudio
                'id' => 2,
                'name' => 'Bit Sof Simple User',
                'username' => 'bit-soft-simple-user',
                'email' => 'user@bitsoft.com',
                'password' => Hash::make('bitsoft123'),
                'email_verified_at' => Carbon::now(),
                'last_login' => Carbon::now(),
                'is_superuser' => 0,
                'first_name' => "Bitsoft",
                'last_name' => "User",
                'is_staff' => 0,
                'is_active' => 1,
                'date_joined' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
