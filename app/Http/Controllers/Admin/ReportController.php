<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SalePostReport;
use App\Models\SalePost;
use App\Models\AdminAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        $reports = SalePostReport::with(['user', 'salePost'])
            ->orderBy('status', 'asc') // Ưu tiên hiện các bài 'Chờ xử lý' trước
            ->orderBy('created_at', 'desc') // TRONG các bài cùng trạng thái, hiện bài mới nhất lên đầu
            ->paginate(15);

        return view('admin.report.index', compact('reports'));
    }

    public function updateStatus(Request $request, $id)
    {
        $report = SalePostReport::findOrFail($id);

        if ($request->action == 'accept') {
            DB::transaction(function () use ($report, $request) {
                $report->update([
                    'status' => 1,
                    'admin_feedback' => $request->admin_feedback ?? 'Bài viết vi phạm đã bị gỡ bỏ.'
                ]);

                if ($report->salePost) {
                    $report->salePost->update(['status' => false]);
                }

                // Ghi Log Hành Động - ĐÃ SỬA LỖI TẠI ĐÂY
                AdminAction::create([
                    'admin_id'    => Auth::id(),
                    'action_type' => 'RESOLVE',
                    'target_type' => 'REPORT', // Bổ sung trường bị thiếu
                    'target_id'   => $report->id, // Bổ sung ID để dễ tra cứu (nếu bảng có cột này)
                    'description' => "Đã CHẤP NHẬN báo cáo ID #$report->id và gỡ bài viết BĐS ID #" . ($report->sale_post_id ?? 'N/A'),
                    'action_time' => now(), // Đảm bảo trùng khớp với cấu trúc bảng
                ]);
            });
            return back()->with('success', 'Đã chấp nhận báo cáo và gỡ bài viết.');
        }

        if ($request->action == 'reject') {
            $report->update([
                'status' => 2,
                'admin_feedback' => $request->admin_feedback ?? 'Báo cáo không chính xác. Bài viết vẫn được giữ lại.'
            ]);

            // Ghi Log Hành Động - ĐÃ SỬA LỖI TẠI ĐÂY
            AdminAction::create([
                'admin_id'    => Auth::id(),
                'action_type' => 'RESOLVE',
                'target_type' => 'REPORT', // Bổ sung trường bị thiếu
                'target_id'   => $report->id,
                'description' => "Đã BÁC BỎ báo cáo ID #$report->id",
                'action_time' => now(),
            ]);

            return back()->with('success', 'Đã bác bỏ báo cáo này.');
        }
    }
}