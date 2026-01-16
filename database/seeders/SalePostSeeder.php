<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SalePost;
use App\Models\SalePostImage;
use App\Models\User;
use App\Models\Category; // QUAN TRỌNG: Import Category

class SalePostSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Đảm bảo có ít nhất 1 user
        $user = User::first() ?: User::factory()->create();

        // 2. Lấy danh sách ID của các Category hiện có (Căn hộ, Nhà phố, Đất...)
        // Nếu database chưa có category nào, hãy tạo mẫu 1 cái để tránh lỗi
        $categoryIds = Category::pluck('id')->toArray();
        if (empty($categoryIds)) {
            $categoryIds[] = Category::create(['name' => 'Chung cư', 'slug' => 'chung-cu'])->id;
        }

        // Kho ảnh Bất động sản
        $realEstateImages = [
            'https://images.pexels.com/photos/106399/pexels-photo-106399.jpeg',
            'https://images.pexels.com/photos/1396122/pexels-photo-1396122.jpeg',
            'https://images.pexels.com/photos/323780/pexels-photo-323780.jpeg',
            'https://images.pexels.com/photos/1643383/pexels-photo-1643383.jpeg',
            'https://images.pexels.com/photos/259588/pexels-photo-259588.jpeg',
            'https://images.pexels.com/photos/2102587/pexels-photo-2102587.jpeg',
            'https://images.pexels.com/photos/1571460/pexels-photo-1571460.jpeg',
            'https://images.pexels.com/photos/276724/pexels-photo-276724.jpeg',
            'https://images.pexels.com/photos/1643384/pexels-photo-1643384.jpeg',
            'https://images.pexels.com/photos/2724749/pexels-photo-2724749.jpeg',
        ];

        $titles = [
            'Căn hộ Studio Vinhomes Smart City',
            'Nhà phố liền kề KĐT Sala',
            'Penthouse Sky Villa View Sông',
            'Biệt thự tân cổ điển Pháp',
            'Chung cư cao cấp Masteri Thảo Điền',
            'Căn hộ Officetel Quận 1',
            'Nhà vườn sinh thái ngoại ô',
            'Shophouse mặt tiền đường lớn',
            'Duplex thông tầng Gem Sky',
            'Nhà phố thương mại Sun Grand'
        ];

        foreach ($titles as $title) {
            $type = rand(0, 1) ? 'sale' : 'rent';

            // Điều chỉnh giá: Sale (tỷ đồng), Rent (triệu đồng)
            $price = ($type === 'sale')
                ? rand(2, 50) * 1000000000
                : rand(5, 50) * 1000000;

            $post = SalePost::create([
                'user_id'      => $user->id,
                'category_id'  => $categoryIds[array_rand($categoryIds)], // MỚI: Gắn ID danh mục ngẫu nhiên
                'type'         => $type,
                'title'        => ($type === 'sale' ? '[BÁN] ' : '[THUÊ] ') . $title,
                'description'  => 'Mô tả chi tiết cho ' . $title . '. Vị trí đắc địa, pháp lý đầy đủ. Nội thất cao cấp nhập khẩu.',
                'price'        => $price,
                'area'         => rand(40, 500),
                'address'      => 'Số ' . rand(1, 200) . ' Đường ABC, Quận ' . rand(1, 12) . ', TP. Hồ Chí Minh',
                'bedrooms'     => rand(1, 5),
                'bathrooms'    => rand(1, 3),
                'is_furnished' => (bool)rand(0, 1),
                'status'       => rand(0, 1), // Trạng thái: 0 hoặc 1
            ]);

            // Gắn 3-5 ảnh mẫu ngẫu nhiên cho mỗi bài viết
            $selectedImages = collect($realEstateImages)->random(rand(3, 5));
            foreach ($selectedImages as $url) {
                SalePostImage::create([
                    'sale_post_id' => $post->id,
                    'image_url'    => $url
                ]);
            }
        }
    }
}