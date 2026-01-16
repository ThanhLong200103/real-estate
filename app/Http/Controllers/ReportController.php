<?php

namespace App\Http\Controllers;

// Chỉ giữ duy nhất dòng use này để trỏ đúng vào thư mục SalePost
use App\Http\Requests\SalePost\StoreReportRequest;
use App\Models\SalePostReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    /**
     * Gửi báo cáo từ phía User
     */
    public function store(StoreReportRequest $request)
    {
        try {
            // 1. Kiểm tra xem User đã báo cáo bài này mà chưa được xử lý không (tránh spam)
            $exists = SalePostReport::where('user_id', Auth::id())
                ->where('sale_post_id', $request->sale_post_id)
                ->where('status', 0) // Chỉ chặn nếu có report đang ở trạng thái Chờ duyệt
                ->exists();

            if ($exists) {
                return back()->with('error', 'Bạn đã gửi báo cáo cho bài viết này. Vui lòng đợi quản trị viên xử lý.');
            }

            // 2. Tạo bản ghi report mới
            SalePostReport::create([
                'user_id'      => Auth::id(),
                'sale_post_id' => $request->sale_post_id,
                'reason'       => $request->reason,
                /**
                 * ĐỒNG BỘ Ở ĐÂY: 
                 * Form gửi lên là 'description' (theo StoreReportRequest bạn vừa sửa)
                 * Database lưu vào cột 'content'
                 */
                'content'      => $request->description,
                'status'       => 0, // Mặc định: Chờ duyệt
            ]);

            return back()->with('success', 'Gửi báo cáo thành công! Chúng tôi sẽ xem xét sớm nhất.');
        } catch (\Exception $e) {
            Log::error("Lỗi gửi Report: " . $e->getMessage());
            return back()->with('error', 'Có lỗi xảy ra khi gửi báo cáo.');
        }
    }

    /**
     * Xem lịch sử báo cáo của User (Dành cho trang Lịch sử)
     */
    public function index()
    {
        // Lấy danh sách report của user kèm thông tin bài viết
        $reports = SalePostReport::with('salePost')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('user.sale-post.report-history', compact('reports'));
    }
}