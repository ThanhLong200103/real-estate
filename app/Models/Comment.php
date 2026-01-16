<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['user_id', 'sale_post_id', 'content'];

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
}