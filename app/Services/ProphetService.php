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
        // Gói toàn bộ logic vào trong Cache::remember
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

            // Khởi tạo tiến trình
            $process = new Process(['python3', $scriptPath, $jsonInput]);

            try {
                // Thực thi script Python
                $process->run();

                // Kiểm tra nếu thực thi thất bại, ném ra Exception theo chuẩn Symfony
                if (!$process->isSuccessful()) {
                    throw new ProcessFailedException($process);
                }

                // Trả về kết quả giải mã JSON nếu thành công
                return json_decode($process->getOutput(), true);
            } catch (ProcessFailedException $e) {
                // Bắt lỗi và trả về thông tin chi tiết
                return [
                    'error' => 'Lỗi AI',
                    'detail' => $e->getMessage() // Trả về thông tin lỗi chi tiết từ Process
                ];
            } catch (\Exception $e) {
                // Bắt các lỗi phát sinh khác (nếu có)
                return [
                    'error' => 'Hệ thống gặp sự cố',
                    'detail' => $e->getMessage()
                ];
            }
        });
    }
}