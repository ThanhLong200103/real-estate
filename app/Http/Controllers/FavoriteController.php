<?php

namespace App\Http\Controllers;

use App\Models\SalePost;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    // Toggle yêu thích (Bấm lần 1 là thích, bấm lại là bỏ)
    public function toggle($id)
    {
        // Cách 1: Sử dụng gợi ý kiểu dữ liệu cho IDE (Hết báo đỏ 100%)
        /** @var User $user */
        $user = Auth::user();

        $post = SalePost::findOrFail($id);

        // Code sẽ chạy bình thường vì method favoritePosts đã tồn tại trong User.php
        $user->favoritePosts()->toggle($id);

        return back()->with('success', 'Đã cập nhật danh sách yêu thích!');
    }

    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        // Lấy danh sách kèm theo ảnh để hiển thị đẹp như EstateHub
        $favorites = $user->favoritePosts()
            ->with('images')
            ->latest()
            ->paginate(12);

        return view('user.sale-post.favorites', compact('favorites'));
    }
}