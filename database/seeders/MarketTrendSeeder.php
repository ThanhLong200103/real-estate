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
        $monthsToSeed = 30; // 2.5 năm dữ liệu (Prophet thích >= 24)

        $this->command->info("Đang tạo dữ liệu xu hướng thị trường (biến động mạnh) cho {$districts->count()} quận...");

        foreach ($districts as $district) {

            // Giá khởi điểm: 40 – 120 triệu / m2
            $currentPrice = rand(40, 120) * 1_000_000;

            for ($i = $monthsToSeed; $i >= 0; $i--) {

                /**
                 * 1️⃣ Xu hướng dài hạn: +0.3% → +0.8% / tháng
                 */
                $trend = rand(3, 8) / 1000;

                /**
                 * 2️⃣ Nhiễu thị trường: -3% → +3%
                 */
                $noise = rand(-30, 30) / 1000;

                /**
                 * 3️⃣ Cú sốc bất thường (xác suất ~15%)
                 */
                $shock = 0;
                if (rand(1, 100) <= 15) {
                    // Sốc âm mạnh hoặc bật tăng
                    $shock = rand(-80, 120) / 1000; // -8% → +12%
                }

                $fluctuation = $trend + $noise + $shock;

                // Áp dụng biến động
                $currentPrice = (int) ($currentPrice * (1 + $fluctuation));

                // Chặn giá không quá thấp
                if ($currentPrice < 15_000_000) {
                    $currentPrice = rand(15, 20) * 1_000_000;
                }

                $data[] = [
                    'district_id'     => $district->id,
                    'month_year'      => Carbon::now()->subMonths($i)->format('Y-m-01'),
                    'avg_price_per_m2' => $currentPrice,
                    'post_count'      => rand(60, 300),
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ];

                // Insert batch
                if (count($data) >= 500) {
                    DB::table('market_trends')->insert($data);
                    $data = [];
                }
            }
        }

        if (!empty($data)) {
            DB::table('market_trends')->insert($data);
        }

        $this->command->info("Tạo dữ liệu market trend thành công");
    }
}