<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\Process\Exception\ProcessFailedException;

class ProphetService
{
    /**
     * Dự báo giá BĐS theo quận/huyện bằng AI Prophet (Python)
     *
     * @param int $districtId
     * @return array
     */
    public function predictByDistrict($districtId)
    {
        // Gói toàn bộ logic cũ vào trong Cache::remember
        return Cache::remember("forecast_district_{$districtId}", 3600, function () use ($districtId) {

            $history = DB::table('market_trends')
                ->where('district_id', $districtId)
                ->orderBy('month_year', 'asc')
                ->get(['month_year as ds', 'avg_price_per_m2 as y']);

            if ($history->count() < 3) {
                return ['error' => 'Dữ liệu tại khu vực này chưa đủ để dự báo AI.'];
            }

            $jsonInput = json_encode($history);
            $scriptPath = base_path('forecast_engine.py');

            $process = new Process(['python', $scriptPath, $jsonInput]);
            $process->run();

            if (!$process->isSuccessful()) {
                return ['error' => 'Lỗi thực thi AI.'];
            }

            return json_decode($process->getOutput(), true);
        });
    }
}