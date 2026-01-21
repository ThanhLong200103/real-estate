<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\SalePost;
use Carbon\Carbon;

class AggregateMarketData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:aggregate-market-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Đang tổng hợp dữ liệu từ sale_posts...');

        // Lấy dữ liệu giá TB theo từng quận và từng tháng
        $data = DB::table('sale_posts')
            ->select(
                'district_id',
                DB::raw("DATE_FORMAT(created_at, '%Y-%m-01') as month_year"),
                DB::raw("AVG(price / area) as avg_price"),
                DB::raw("COUNT(id) as total_posts")
            )
            ->whereNotNull('district_id')
            ->where('status', 1) // Chỉ lấy bài đã duyệt (nếu có)
            ->groupBy('district_id', 'month_year')
            ->get();

        foreach ($data as $item) {
            DB::table('market_trends')->updateOrInsert(
                [
                    'district_id' => $item->district_id,
                    'month_year' => $item->month_year
                ],
                [
                    'avg_price_per_m2' => $item->avg_price,
                    'total_posts' => $item->total_posts,
                    'updated_at' => now()
                ]
            );
        }

        $this->info('Tổng hợp dữ liệu thành công!');
    }
}