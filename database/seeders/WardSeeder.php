<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\District;

class WardSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('wards')->truncate();
        Schema::enableForeignKeyConstraints();

        // Định nghĩa mảng dữ liệu: 'Tên Quận' => ['Danh sách Phường']
        $data = [
            // HÀ NỘI
            'Hà Đông' => ['Mộ Lao', 'Văn Quán', 'Phúc La', 'Quang Trung', 'Yết Kiêu', 'Nguyễn Trãi', 'Vạn Phúc', 'La Khê', 'Phú Lãm', 'Phú Lương', 'Dương Nội'],
            'Cầu Giấy' => ['Dịch Vọng', 'Dịch Vọng Hậu', 'Quan Hoa', 'Mai Dịch', 'Nghĩa Tân', 'Nghĩa Đô', 'Yên Hòa', 'Trung Hòa'],
            'Nam Từ Liêm' => ['Mỹ Đình 1', 'Mỹ Đình 2', 'Mễ Trì', 'Phú Đô', 'Tây Mỗ', 'Đại Mỗ', 'Trung Văn', 'Phương Canh'],
            'Hoàn Kiếm' => ['Cửa Đông', 'Cửa Nam', 'Chương Dương', 'Đồng Xuân', 'Hàng Bạc', 'Hàng Bài', 'Hàng Bông', 'Hàng Đào', 'Tràng Tiền'],
            'Thanh Xuân' => ['Hạ Đình', 'Khương Đình', 'Khương Mai', 'Khương Trung', 'Kim Giang', 'Nhân Chính', 'Thanh Xuân Bắc', 'Thanh Xuân Trung'],

            // TP. HỒ CHÍ MINH
            'Quận 1' => ['Bến Nghé', 'Bến Thành', 'Cô Giang', 'Cầu Kho', 'Cầu Ông Lãnh', 'Đa Kao', 'Nguyễn Cư Trinh', 'Nguyễn Thái Bình', 'Phạm Ngũ Lão', 'Tân Định'],
            'Quận 7' => ['Tân Phong', 'Phú Mỹ', 'Tân Quy', 'Tân Kiểng', 'Tân Thuận Đông', 'Tân Thuận Tây', 'Phú Thuận', 'Bình Thuận'],
            'Bình Thạnh' => ['Phường 1', 'Phường 2', 'Phường 3', 'Phường 11', 'Phường 13', 'Phường 15', 'Phường 17', 'Phường 19', 'Phường 21', 'Phường 25', 'Phường 27'],
            'Thủ Đức' => ['Linh Đông', 'Linh Tây', 'Linh Chiểu', 'Linh Trung', 'Linh Xuân', 'Hiệp Bình Chánh', 'Hiệp Bình Phước', 'Tam Bình', 'Tam Phú', 'Bình Thọ'],
            'Quận 10' => ['Phường 1', 'Phường 2', 'Phường 4', 'Phường 8', 'Phường 10', 'Phường 12', 'Phường 14', 'Phường 15'],

            // ĐÀ NẴNG
            'Hải Châu' => ['Hải Châu I', 'Hải Châu II', 'Thạch Thang', 'Thanh Bình', 'Thuận Phước', 'Hòa Thuận Đông', 'Hòa Thuận Tây', 'Nam Dương', 'Phước Ninh'],
            'Sơn Trà' => ['An Hải Bắc', 'An Hải Tây', 'An Hải Đông', 'Phước Mỹ', 'Quang Thọ', 'Mân Thái', 'Nại Hiên Đông'],

            // BÌNH DƯƠNG
            'Thủ Dầu Một' => ['Hiệp Thành', 'Phú Lợi', 'Phú Cường', 'Phú Hòa', 'Phú Thọ', 'Chánh Nghĩa', 'Định Hòa', 'Hòa Phú', 'Phú Tân'],
            'Thuận An' => ['Lái Thiêu', 'An Thạnh', 'Vĩnh Phú', 'Bình Hòa', 'Thuận Giao', 'An Phú', 'Bình Chuẩn'],
        ];

        $totalWards = 0;
        foreach ($data as $districtName => $wards) {
            // Tìm District ID dựa trên tên để đảm bảo chính xác
            $district = District::where('name', $districtName)->first();

            if ($district) {
                $insertData = [];
                foreach ($wards as $wardName) {
                    $insertData[] = [
                        'name' => str_contains($wardName, 'Phường') ? $wardName : 'Phường ' . $wardName,
                        'district_id' => $district->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                    $totalWards++;
                }
                DB::table('wards')->insert($insertData);
            }
        }

        $this->command->info("Đã bơm thêm $totalWards phường/xã vào database!");
    }
}