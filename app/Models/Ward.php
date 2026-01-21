<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ward extends Model
{
    // Thêm dòng này để tránh Laravel tìm bảng 'wardes' (sai quy tắc số nhiều)
    protected $table = 'wards';

    public $timestamps = false;

    protected $fillable = ['name', 'district_id'];

    public function district()
    {
        return $this->belongsTo(District::class);
    }
}