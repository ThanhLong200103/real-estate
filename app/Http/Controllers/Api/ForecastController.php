<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ProphetService;

class ForecastController extends Controller
{
    public function show(Request $request, ProphetService $service)
    {
        $districtId = $request->query('district_id');
        $result = $service->predictByDistrict($districtId);

        // Nếu AI báo lỗi hoặc dữ liệu quá ảo, trả về mã lỗi để ẩn Widget
        if (isset($result['error']) || abs($result['month']) > 100) {
            return response()->json(['status' => 'insufficient_data']);
        }

        return response()->json($result);
    }
}