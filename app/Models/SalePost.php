<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalePost extends Model
{
    protected $table = 'sale_posts';
    protected $fillable = [
        'user_id', // Phải khớp với cột trong Migration và dòng code ở Controller trên
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
}