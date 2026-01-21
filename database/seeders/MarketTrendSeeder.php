<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\District;
use Carbon\Carbon;

class MarketTrendSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('market_trends')->truncate();

        $districts = District::all();
        $data = [];
        $monthsToSeed = 24; // 2 năm dữ liệu để vẽ biểu đồ dài hạn

        $this->command->info("Đang tạo dữ liệu xu hướng thị trường cho " . $districts->count() . " quận huyện...");

        foreach ($districts as $district) {
            // Giá khởi điểm ngẫu nhiên cho mỗi quận
            $currentPrice = rand(25, 80) * 1_000_000;

            for ($i = $monthsToSeed; $i >= 0; $i--) {
                // Biến động giá từ -2% đến +4% để tạo xu hướng tăng nhẹ (thực tế hơn)
                $fluctuation = rand(-20, 40) / 1000;
                $currentPrice = (int) ($currentPrice * (1 + $fluctuation));

                $data[] = [
                    'district_id'      => $district->id,
                    'month_year'        => Carbon::now()->subMonths($i)->format('Y-m-01'),
                    'avg_price_per_m2'  => $currentPrice,
                    'post_count'        => rand(50, 200),
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ];

                // Insert theo lô (batch) 500 bản ghi một lần để không quá tải bộ nhớ
                if (count($data) >= 500) {
                    DB::table('market_trends')->insert($data);
                    $data = [];
                }
            }
        }

        // Insert nốt số dữ liệu còn dư
        if (!empty($data)) {
            DB::table('market_trends')->insert($data);
        }

        $this->command->info("Đã tạo xong dữ liệu Market Trends!");
    }
}