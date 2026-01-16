<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // Cho phép lưu dữ liệu vào các cột này
    protected $fillable = ['name', 'slug'];

    /**
     * Quan hệ: Một danh mục có nhiều bài đăng
     */
    public function salePosts()
    {
        return $this->hasMany(SalePost::class, 'category_id');
    }
}