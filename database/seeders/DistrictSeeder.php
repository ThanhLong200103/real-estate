<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Province;
use App\Models\District;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DistrictSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('districts')->truncate();
        Schema::enableForeignKeyConstraints();

        // Danh sách dữ liệu mẫu cho các tỉnh thành lớn
        $data = [
            'Hà Nội' => [
                'Ba Đình',
                'Hoàn Kiếm',
                'Tây Hồ',
                'Cầu Giấy',
                'Đống Đa',
                'Hai Bà Trưng',
                'Thanh Xuân',
                'Hoàng Mai',
                'Long Biên',
                'Hà Đông',
                'Nam Từ Liêm',
                'Bắc Từ Liêm',
                'Thanh Trì',
                'Gia Lâm',
                'Đông Anh'
            ],
            'TP. Hồ Chí Minh' => [
                'Quận 1',
                'Quận 3',
                'Quận 4',
                'Quận 5',
                'Quận 6',
                'Quận 7',
                'Quận 8',
                'Quận 10',
                'Quận 11',
                'Quận 12',
                'Bình Thạnh',
                'Tân Bình',
                'Tân Phú',
                'Gò Vấp',
                'Phú Nhuận',
                'Bình Tân',
                'Thủ Đức',
                'Hóc Môn',
                'Củ Chi',
                'Nhà Bè',
                'Bình Chánh',
                'Cần Giờ'
            ],
            'Đà Nẵng' => ['Hải Châu', 'Thanh Khê', 'Sơn Trà', 'Ngũ Hành Sơn', 'Liên Chiểu', 'Cẩm Lệ', 'Hòa Vang', 'Hoàng Sa'],
            'Hải Phòng' => ['Hồng Bàng', 'Lê Chân', 'Ngô Quyền', 'Kiến An', 'Hải An', 'Đồ Sơn', 'Dương Kinh', 'Thủy Nguyên', 'An Dương', 'An Lão'],
            'Cần Thơ' => ['Ninh Kiều', 'Bình Thủy', 'Cái Răng', 'Ô Môn', 'Thốt Nốt', 'Phong Điền', 'Thới Lai', 'Cờ Đỏ', 'Vĩnh Thạnh'],
            'Bình Dương' => ['Thủ Dầu Một', 'Thuận An', 'Dĩ An', 'Tân Uyên', 'Bến Cát', 'Bàu Bàng', 'Bắc Tân Uyên', 'Dầu Tiếng', 'Phú Giáo'],
            'Đồng Nai' => ['Biên Hòa', 'Long Khánh', 'Long Thành', 'Nhơn Trạch', 'Trảng Bom', 'Thống Nhất', 'Cẩm Mỹ', 'Vĩnh Cửu', 'Xuân Lộc'],
            'Khánh Hòa' => ['Nha Trang', 'Cam Ranh', 'Ninh Hòa', 'Vạn Ninh', 'Diên Khánh', 'Khánh Vĩnh', 'Khánh Sơn', 'Cam Lâm'],
            'Quảng Ninh' => ['Hạ Long', 'Móng Cái', 'Cẩm Phả', 'Uông Bí', 'Đông Triều', 'Quảng Yên', 'Vân Đồn', 'Tiên Yên', 'Cô Tô'],
            'Lâm Đồng' => ['Đà Lạt', 'Bảo Lộc', 'Lạc Dương', 'Đơn Dương', 'Đức Trọng', 'Lâm Hà', 'Di Linh', 'Bảo Lâm', 'Đạ Huoai'],
            'Bà Rịa - Vũng Tàu' => ['Vũng Tàu', 'Bà Rịa', 'Phú Mỹ', 'Châu Đức', 'Xuyên Mộc', 'Đất Đỏ', 'Long Điền', 'Côn Đảo'],
            'Kiên Giang' => ['Rạch Giá', 'Hà Tiên', 'Phú Quốc', 'Kiên Lương', 'Hòn Đất', 'Tân Hiệp', 'Châu Thành', 'Giồng Riềng'],
            'Thừa Thiên Huế' => ['Huế', 'Hương Thủy', 'Hương Trà', 'Phong Điền', 'Quảng Điền', 'Phú Vang', 'Phú Lộc', 'A Lưới'],
        ];

        $this->command->info("Đang chèn dữ liệu quận huyện...");

        foreach ($data as $provinceName => $districts) {
            $province = Province::where('name', $provinceName)->first();

            if ($province) {
                $insertData = [];
                foreach ($districts as $districtName) {
                    $insertData[] = [
                        'province_id' => $province->id,
                        'name' => $districtName,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                // Chèn hàng loạt (Bulk Insert) để tăng tốc độ
                District::insert($insertData);
                $this->command->info("- Đã thêm " . count($districts) . " quận/huyện cho: $provinceName");
            } else {
                $this->command->warn("! Không tìm thấy tỉnh: $provinceName (Bỏ qua)");
            }
        }
    }
}