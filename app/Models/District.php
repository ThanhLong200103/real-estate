<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;

    // Tên bảng trong database
    protected $table = 'districts';

    // Các cột cho phép thêm dữ liệu nhanh
    protected $fillable = ['province_id', 'name', 'code'];

    /**
     * Một Huyện thuộc về một Tỉnh (Belongs To)
     */
    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id', 'id');
    }
}