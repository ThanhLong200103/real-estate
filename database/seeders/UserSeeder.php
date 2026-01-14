<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin', // Đổi 'Admin' thành 'admin'
            'status' => 1,
            'phone_number' => '0900000000',
        ]);

        // User thường
        User::create([
            'name' => 'Nguyen Van A',
            'email' => 'user@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'user', // Đổi 'User' thành 'user'
            'status' => 1,
            'phone_number' => '0911111111',
        ]);
    }
}