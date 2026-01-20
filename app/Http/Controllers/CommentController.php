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
            'parent_id' => 'nullable|exists:comments,id', // Kiểm tra parent_id nếu có phải tồn tại trong bảng comments
        ]);

        Comment::create([
            'user_id' => Auth::id(),
            'sale_post_id' => $postId,
            'content' => $request->content,
            'parent_id' => $request->parent_id, // Lưu ID của bình luận cha (null nếu là bình luận gốc)
        ]);

        return back()->with('success', 'Cảm ơn bạn đã để lại ý kiến!');
    }

    public function destroy(Comment $comment)
    {
        // Khi xóa comment cha, các comment con sẽ tự động xóa nhờ "cascade" ở Migration
        if (Auth::id() === $comment->user_id || Auth::user()->role === 'admin') {
            $comment->delete();
            return back()->with('success', 'Đã xóa bình luận.');
        }

        return back()->with('error', 'Bạn không có quyền xóa bình luận này.');
    }
}