<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Category::create(['name' => 'Căn hộ', 'slug' => 'can-ho']);
        \App\Models\Category::create(['name' => 'Nhà phố', 'slug' => 'nha-pho']);
        \App\Models\Category::create(['name' => 'Đất nền', 'slug' => 'dat-nen']);
    }
}