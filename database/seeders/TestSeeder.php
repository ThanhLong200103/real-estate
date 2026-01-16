<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SalePost;
use App\Models\User;
use App\Models\Category;

class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy User có role User đầu tiên
        $user = User::where('role', 'User')->first();

        // Kiểm tra nếu không tìm thấy user
        if (!$user) {
            $this->command->error("Không tìm thấy user có role là 'User'. Vui lòng chạy UserSeeder trước.");
            return;
        }

        // Lấy ID của danh mục để gán vào bài đăng (để không bị hiện "Chưa phân loại")
        $canHo = Category::where('slug', 'can-ho')->first();
        $nhaPho = Category::where('slug', 'nha-pho')->first();

        SalePost::create([
            'user_id'      => $user->id,
            'category_id'  => $nhaPho->id ?? null, // Gán ID Nhà phố
            'type'         => 'sale',
            'title'        => 'Bán nhà quận 1',
            'description'  => 'Nhà trung tâm, sổ hồng riêng',
            'price'        => 3500000000,
            'area'         => 80,
            'address'      => 'Quận 1, TP.HCM',
            'bedrooms'     => 3,
            'bathrooms'    => 2,
            'is_furnished' => true,
            'status'       => true,
        ]);

        SalePost::create([
            'user_id'      => $user->id,
            'category_id'  => $canHo->id ?? null, // Gán ID Căn hộ
            'type'         => 'rent',
            'title'        => 'Căn hộ chung cư quận 7',
            'description'  => 'View đẹp, đầy đủ nội thất',
            'price'        => 15000000, // Thường thuê nên để giá thấp hơn bán
            'area'         => 65,
            'address'      => 'Quận 7, TP.HCM',
            'bedrooms'     => 2,
            'bathrooms'    => 2,
            'is_furnished' => true,
            'status'       => true, // Để true để hiện lên trang chủ luôn
        ]);
    }
}