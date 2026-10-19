<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Store Administrator',
            'email' => 'admin2@example.com',
            'password' => Hash::make('adminexample'),
            'phone' => '01700000000',
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Demo Customer',
            'email' => 'customer2@example.com',
            'password' => Hash::make('customerexample'),
            'phone' => '01800000000',
            'role' => 'customer',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Sarah Ahmed',
            'email' => 'sarah@example.com',
            'password' => Hash::make('password'),
            'phone' => '01911111111',
            'role' => 'customer',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Nusrat Jahan',
            'email' => 'nusrat@example.com',
            'password' => Hash::make('123456'),
            'phone' => '01622222222',
            'role' => 'customer',
            'email_verified_at' => now(),
        ]);
    }
}

