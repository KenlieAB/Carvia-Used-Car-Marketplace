<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin Platform
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@mobilbekas.com',
            'password' => Hash::make('password'),
            'phone' => '628111222333',
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Demo User Penjual & Pembeli
        User::create([
            'name' => 'Budi Pratama',
            'email' => 'budi@example.com',
            'password' => Hash::make('password'),
            'phone' => '6281234567890',
            'role' => 'user',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Siti Nurhaliza',
            'email' => 'siti@example.com',
            'password' => Hash::make('password'),
            'phone' => '6281987654321',
            'role' => 'user',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Hendro Wijaya',
            'email' => 'hendro@example.com',
            'password' => Hash::make('password'),
            'phone' => '6282155566677',
            'role' => 'user',
            'email_verified_at' => now(),
        ]);
    }
}
