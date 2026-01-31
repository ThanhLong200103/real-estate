<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MarketTrendController extends Controller
{
    public function getForecast($district_id)
    {
        // 1. Lấy dữ liệu lịch sử
        $historyData = DB::table('market_trends')
            ->where('district_id', $district_id)
            ->orderBy('month_year', 'asc')
            ->get();

        if ($historyData->isEmpty()) {
            return response()->json(['error' => 'Chưa có dữ liệu xu hướng cho quận này'], 404);
        }

        // 2. Format history giống AI
        $history = $historyData->map(function ($item) {
            return [
                'ds' => Carbon::parse($item->month_year)->format('Y-m'),
                'y'  => round($item->avg_price_per_m2, 2)
            ];
        });

        // 3. Dữ liệu tính tăng trưởng
        $descData = $historyData->reverse()->values();

        $latest = $descData->get(0);
        $m1  = $descData->get(1);
        $m3  = $descData->get(3);
        $m12 = $descData->get(12);

        $currentVal = $latest?->avg_price_per_m2 ?? 0;

        // 4.  GIẢ LẬP 12 THÁNG TƯƠNG LAI (future)
        $future = [];
        $baseDate  = Carbon::parse($latest->month_year);
        $basePrice = $currentVal;

        for ($i = 1; $i <= 12; $i++) {
            $basePrice *= 1.02; // +2% / tháng (baseline)
            $future[] = [
                'ds' => $baseDate->copy()->addMonths($i)->format('Y-m'),
                'y'  => round($basePrice, 2)
            ];
        }

        return response()->json([
            'current' => round($currentVal, 2),

            'month'   => $this->calculateGrowth($currentVal, $m1?->avg_price_per_m2),
            'quarter' => $this->calculateGrowth($currentVal, $m3?->avg_price_per_m2),
            'year'    => $this->calculateGrowth($currentVal, $m12?->avg_price_per_m2),

            // QUAN TRỌNG: key giống AI
            'history' => $history,
            'future'  => $future
        ]);
    }

    private function calculateGrowth($current, $past)
    {
        if (!$past || $past == 0 || !$current) return 0;
        return round((($current - $past) / $past) * 100, 2);
    }
}