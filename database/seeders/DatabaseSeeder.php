<?php

namespace Database\Seeders;

use App\Models\News;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();



        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call([
            UserSeeder::class,     // 1. Tạo người dùng trước
            CategorySeeder::class, // 2. Tạo danh mục để bài đăng có cái mà trỏ vào
            TestSeeder::class,     // 3. Tạo dữ liệu test
            SalePostSeeder::class,
        ]);
        $this->call(NewsSeederfinal::class);
        $this->call(NewsSeeder::class);
    }
}