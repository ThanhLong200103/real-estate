<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy tất cả ID của bài đăng
        $postIds = DB::table('sale_posts')->pluck('id');

        foreach ($postIds as $id) {
            DB::table('sale_post_images')->insert([
                'sale_post_id' => $id,
                'image_url'    => 'https://picsum.photos/seed/' . $id . '/800/600',
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
        }
    }
}