<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalePost extends Model
{
    protected $table = 'sale_posts';

    protected $fillable = [
        'user_id',
        'category_id',
        'type',
        'title',
        'description',
        'price',
        'area',
        'address',
        'bedrooms',
        'bathrooms',
        'is_furnished',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function images()
    {
        return $this->hasMany(SalePostImage::class, 'sale_post_id');
    }

    public function chats()
    {
        return $this->hasMany(SalePostChat::class);
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites', 'sale_post_id', 'user_id');
    }
    // Một bài đăng có nhiều bình luận
    public function comments()
    {
        return $this->hasMany(Comment::class)->latest(); // Lấy bình luận mới nhất lên đầu
    }
    // Thêm hàm này vào trong class SalePost
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}