<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;

    // Tên bảng trong database (nếu bạn đặt tên bảng là provinces thì không cần dòng này)
    protected $table = 'provinces';

    // Các cột cho phép thêm dữ liệu nhanh
    protected $fillable = ['name', 'code'];

    /**
     * Một Tỉnh có nhiều Huyện (One-to-Many)
     */
    public function districts()
    {
        return $this->hasMany(District::class, 'province_id', 'id');
    }
    public function sale_posts()
    {
        return $this->hasMany(SalePost::class, 'province_id');
    }
}