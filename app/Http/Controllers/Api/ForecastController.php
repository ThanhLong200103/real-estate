<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ProphetService;
use App\Http\Controllers\Api\MarketTrendController;

class ForecastController extends Controller
{
    public function show(Request $request, ProphetService $service)
    {
        $districtId = $request->query('district_id');

        // 1. Thử dùng AI Prophet trước
        $result = $service->predictByDistrict($districtId);

        // 2. Nếu AI lỗi hoặc kết quả không tin cậy → fallback giả lập
        if (
            isset($result['error']) ||
            !isset($result['month']) ||
            abs($result['month']) > 100
        ) {
            // Fallback sang dữ liệu giả lập +2%
            return app(MarketTrendController::class)->getForecast($districtId);
        }

        // 3. AI hợp lệ → trả kết quả AI
        return response()->json($result);
    }
}