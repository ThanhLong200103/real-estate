<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MarketTrendController extends Controller
{
    public function getForecast($district_id)
    {
        // 1. Lấy dữ liệu lịch sử để vẽ biểu đồ (Sắp xếp từ cũ đến mới)
        $historyData = DB::table('market_trends')
            ->where('district_id', $district_id)
            ->orderBy('month_year', 'asc')
            ->get();

        if ($historyData->isEmpty()) {
            return response()->json(['error' => 'Chưa có dữ liệu xu hướng cho quận này'], 404);
        }

        $history = $historyData->map(function ($item) {
            return [
                'ds' => Carbon::parse($item->month_year)->format('m/Y'),
                'y' => (int) $item->avg_price_per_m2
            ];
        });

        // 2. Lấy các mốc dữ liệu để tính toán tăng trưởng (Sắp xếp từ mới đến cũ)
        $descData = $historyData->reverse()->values();

        $latest = $descData->get(0); // Tháng gần nhất
        $m1 = $descData->get(1);     // 1 tháng trước
        $m3 = $descData->get(3);     // 3 tháng trước
        $m12 = $descData->get(12);   // 1 năm trước

        // 3. Giả lập giá trị dự báo cho tháng tiếp theo (Prophet giả lập)
        
        // “Đây là mô hình baseline (fallback) khi AI không đủ dữ liệu.”
        $forecastValue = $latest ? $latest->avg_price_per_m2 * 1.02 : 0;

        return response()->json([
            // Dữ liệu cho biểu đồ
            'history' => $history,
            'forecast_value' => round($forecastValue),

            // Dữ liệu cho các thẻ thống kê %
            'month' => $this->calculateGrowth($latest?->avg_price_per_m2, $m1?->avg_price_per_m2),
            'quarter' => $this->calculateGrowth($latest?->avg_price_per_m2, $m3?->avg_price_per_m2),
            'year' => $this->calculateGrowth($latest?->avg_price_per_m2, $m12?->avg_price_per_m2),

            // Các key này dùng cho Fetch JavaScript bạn vừa thêm
            'one_month' => $this->calculateGrowth($latest?->avg_price_per_m2, $m1?->avg_price_per_m2),
            'three_months' => $this->calculateGrowth($latest?->avg_price_per_m2, $m3?->avg_price_per_m2),
            'one_year' => $this->calculateGrowth($latest?->avg_price_per_m2, $m12?->avg_price_per_m2),
        ]);
    }

    private function calculateGrowth($current, $past)
    {
        if (!$past || $past == 0 || !$current) return 0;
        return round((($current - $past) / $past) * 100, 2);
    }
}