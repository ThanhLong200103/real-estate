<?php

namespace App\Providers;

use App\Models\SalePost;
use App\Models\SalePostReport;
use App\Models\AdminAction;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('admin.layout', function ($view) {
            // 1. Đếm số bài đăng đang chờ duyệt (status = false)
            $pendingPostsCount = SalePost::where('status', false)->count();

            // 2. Đếm số báo cáo chưa xử lý (Giả sử status = 0 là chưa xử lý)
            $pendingReportsCount = SalePostReport::where('status', 0)->count();

            // 3. Đếm số hành động Admin trong ngày hôm nay để hiện nốt đỏ Nhật ký
            $todayActionsCount = AdminAction::whereDate('created_at', today())->count();

            // Chia sẻ tất cả biến sang view admin.layout
            $view->with([
                'pendingPostsCount' => $pendingPostsCount,
                'pendingReportsCount' => $pendingReportsCount,
                'todayActionsCount' => $todayActionsCount
            ]);
        });
    }
}