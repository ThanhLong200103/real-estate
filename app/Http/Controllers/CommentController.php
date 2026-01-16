<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, $postId)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        Comment::create([
            'user_id' => Auth::id(),
            'sale_post_id' => $postId,
            'content' => $request->content,
        ]);

        return back()->with('success', 'Bình luận của bạn đã được gửi!');
    }

    public function destroy(Comment $comment)
    {
        // Kiểm tra quyền xóa (chỉ admin hoặc chủ comment)
        if (Auth::id() === $comment->user_id || Auth::user()->role === 'admin') {
            $comment->delete();
            return back()->with('success', 'Đã xóa bình luận.');
        }

        return back()->with('error', 'Bạn không có quyền xóa bình luận này.');
    }
}