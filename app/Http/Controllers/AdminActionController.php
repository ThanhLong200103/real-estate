<?php

namespace App\Http\Controllers;

use App\Models\AdminAction;
use Illuminate\Http\Request;

// Dòng số 8 phải là AdminActionController, không được là AdminAction
class AdminActionController extends Controller
{
    /**
     * Hiển thị danh sách nhật ký
     */
    public function index()
    {
        // Lấy dữ liệu từ Model AdminAction
        $actions = AdminAction::with('admin')->latest()->paginate(20);

        // Thêm dòng này nếu muốn hiện thông báo khi vừa vào trang nhật ký
        return view('admin.action.index', compact('actions'))
            ->with('success', 'Tải dữ liệu nhật ký thành công!');
    }
}