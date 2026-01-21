<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SalePost;
use App\Models\User;
use App\Models\Category;
use App\Models\Province;
use App\Models\District;
use App\Models\Ward;

class SalePostSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Lấy danh sách ID cơ bản
        $userIds = User::pluck('id')->toArray();
        $categoryIds = Category::pluck('id')->toArray();

        // Kiểm tra xem đã có dữ liệu Ward chưa
        if (Ward::count() === 0) {
            $this->command->error("Bảng wards đang trống! Hãy chạy WardSeeder trước khi chạy SalePostSeeder.");
            return;
        }

        // 2. Định nghĩa các mẫu tiêu đề và mô tả (Kịch bản)
        $scenarios = [
            [
                'title' => 'Bán gấp nhà phố, hẻm xe hơi, sổ hồng riêng',
                'desc' => 'Cần tiền kinh doanh bán gấp nhà phố. Diện tích rộng, 1 trệt 2 lầu, 3 phòng ngủ. Khu dân cư an ninh, gần chợ và trường học.',
                'price_range' => [3000000000, 10000000000], // 3 tỷ - 10 tỷ
                'area_range' => [40, 80]
            ],
            [
                'title' => 'Căn hộ chung cư cao cấp view cực đẹp',
                'desc' => 'Chính chủ nhượng lại căn hộ 2 phòng ngủ. Nội thất đầy đủ, cao cấp, chỉ việc xách vali vào ở. Tiện ích: hồ bơi, gym, công viên.',
                'price_range' => [2000000000, 5000000000], // 2 tỷ - 5 tỷ
                'area_range' => [50, 100]
            ],
            [
                'title' => 'Lô đất nền tiềm năng, thích hợp đầu tư sinh lời',
                'desc' => 'Đất nền phân lô, thổ cư 100%. Đường nhựa rộng, gần khu công nghiệp lớn. Pháp lý rõ ràng, sang tên trong ngày.',
                'price_range' => [1000000000, 3000000000], // 1 tỷ - 3 tỷ
                'area_range' => [80, 150]
            ]
        ];

        $totalRecords = 100; // SỐ LƯỢNG BÀI ĐĂNG BẠN MUỐN TẠO
        $this->command->info("Đang tạo $totalRecords bài đăng bất động sản...");

        for ($i = 0; $i < $totalRecords; $i++) {
            // CÁCH FIX LỖI: Bốc ngẫu nhiên 1 Phường trước, sau đó truy ngược ra Quận và Tỉnh
            // Việc này đảm bảo 100% bài đăng luôn có đầy đủ 3 cấp địa giới hành chính.
            $ward = Ward::inRandomOrder()->first();
            $district = District::find($ward->district_id);
            $province = Province::find($district->province_id);

            // Chọn ngẫu nhiên 1 kịch bản nội dung
            $scene = $scenarios[array_rand($scenarios)];

            SalePost::create([
                'user_id'     => $userIds[array_rand($userIds)],
                'category_id' => $categoryIds[array_rand($categoryIds)],
                'province_id' => $province->id,
                'district_id' => $district->id,
                'ward_id'     => $ward->id,
                'title'       => $scene['title'] . " tại " . $district->name . " (#" . ($i + 1) . ")",
                'description' => $scene['desc'] . " Địa chỉ cụ thể tại " . $ward->name . ", " . $district->name . ".",
                'price'       => rand($scene['price_range'][0], $scene['price_range'][1]),
                'area'        => rand($scene['area_range'][0], $scene['area_range'][1]),
                'status'      => 1,
                'address'     => "Số " . rand(1, 200) . " đường chính, " . $ward->name,
                'created_at'  => now()->subDays(rand(0, 30)), // Ngày đăng ngẫu nhiên trong tháng qua

                'type'        => array_rand(['sale' => 'sale', 'rent' => 'rent']),
            ]);
        }

        $this->command->info("Đã tạo thành công dữ liệu giả!");
    }
}