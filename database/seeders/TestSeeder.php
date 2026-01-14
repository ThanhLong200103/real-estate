<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\SalePost;
use App\Models\User;

class TestSeeder extends Seeder

{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('role', 'User')->first();

        SalePost::create([
            'user_id' => $user->id,
            'title' => 'Bán nhà quận 1',
            'description' => 'Nhà trung tâm, sổ hồng riêng',
            'price' => 3500000000,
            'area' => 80,
            'address' => 'Quận 1, TP.HCM',
            'bedrooms' => 3,
            'bathrooms' => 2,
            'is_furnished' => true,
            'status' => true,
        ]);

        SalePost::create([
            'user_id' => $user->id,
            'title' => 'Căn hộ chung cư quận 7',
            'description' => 'View đẹp, đầy đủ nội thất',
            'price' => 2200000000,
            'area' => 65,
            'address' => 'Quận 7, TP.HCM',
            'bedrooms' => 2,
            'bathrooms' => 2,
            'is_furnished' => true,
            'status' => false,
        ]);
    }
}
