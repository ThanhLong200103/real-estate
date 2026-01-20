<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    // Bổ sung parent_id vào fillable để có thể lưu từ form
    protected $fillable = ['user_id', 'sale_post_id', 'content', 'parent_id'];

    // Một bình luận thuộc về một người dùng
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Một bình luận thuộc về một bài đăng
    public function salePost()
    {
        return $this->belongsTo(SalePost::class);
    }

    /**
     * Quan hệ lấy các phản hồi (bình luận con)
     * Một bình luận có thể có nhiều phản hồi
     */
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id')->orderBy('created_at', 'asc');
    }

    /**
     * Quan hệ lấy bình luận cha
     * Một phản hồi sẽ thuộc về một bình luận gốc
     */
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }
}