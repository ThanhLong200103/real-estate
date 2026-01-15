<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SalePost;
use App\Models\SalePostImage;
use App\Models\User;

class SalePostSeeder extends Seeder
{
    public function run(): void
    {
        // Đảm bảo có ít nhất 1 user để gắn ID
        $user = User::first() ?: User::factory()->create();

        // Kho ảnh Bất động sản "bất tử" - Đã kiểm tra từng link
        $realEstateImages = [
            // Ngoại thất - Nhà phố/Biệt thự
            'https://images.pexels.com/photos/106399/pexels-photo-106399.jpeg',
            'https://images.pexels.com/photos/1396122/pexels-photo-1396122.jpeg',
            'https://images.pexels.com/photos/323780/pexels-photo-323780.jpeg',
            'https://images.pexels.com/photos/1643383/pexels-photo-1643383.jpeg',
            'https://images.pexels.com/photos/259588/pexels-photo-259588.jpeg',
            'https://images.pexels.com/photos/2102587/pexels-photo-2102587.jpeg',

            // Nội thất - Phòng khách
            'https://images.pexels.com/photos/1571460/pexels-photo-1571460.jpeg',
            'https://images.pexels.com/photos/276724/pexels-photo-276724.jpeg',
            'https://images.pexels.com/photos/1643384/pexels-photo-1643384.jpeg',
            'https://images.pexels.com/photos/2724749/pexels-photo-2724749.jpeg',

            // Phòng ngủ & Bếp
            'https://images.pexels.com/photos/271618/pexels-photo-271618.jpeg',
            'https://images.pexels.com/photos/1457842/pexels-photo-1457842.jpeg',
            'https://images.pexels.com/photos/534151/pexels-photo-534151.jpeg',
            'https://images.pexels.com/photos/1080721/pexels-photo-1080721.jpeg',

            // View ban công / Sân vườn
            'https://images.pexels.com/photos/206172/pexels-photo-206172.jpeg',
            'https://images.pexels.com/photos/280222/pexels-photo-280222.jpeg',
            'https://images.pexels.com/photos/7031408/pexels-photo-7031408.jpeg',
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
            // Tạo bài đăng với đầy đủ thông tin để tránh lỗi SQL null
            $post = SalePost::create([
                'user_id'     => $user->id,
                'title'       => $title,
                'description' => 'Mô tả chi tiết cho ' . $title . '. Vị trí đắc địa, pháp lý đầy đủ, sổ hồng trao tay. Nội thất cao cấp nhập khẩu từ châu Âu.',
                'price'       => rand(2, 50) * 1000000000, // Giá từ 2 tỷ đến 50 tỷ
                'area'        => rand(40, 500),
                'address'     => 'Số ' . rand(1, 200) . ' Đường ABC, TP. Hồ Chí Minh',
                'bedrooms'    => rand(1, 5),
                'bathrooms'   => rand(1, 3),
                'is_furnished' => (bool)rand(0, 1),
                'status'      => rand(0, 1),
            ]);

            // Mỗi bài lấy ngẫu nhiên 4 ảnh từ kho ảnh trên
            $selectedImages = collect($realEstateImages)->random(4);

            foreach ($selectedImages as $url) {
                SalePostImage::create([
                    'sale_post_id' => $post->id,
                    'image_url'    => $url
                ]);
            }
        }
    }
}