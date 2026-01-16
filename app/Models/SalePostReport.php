<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalePostReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'sale_post_id',
        'reason',
        'content',
        'status',
        'admin_feedback'
    ];

    // Quan hệ với người báo cáo
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Quan hệ với bài viết bị báo cáo
    public function salePost()
    {
        return $this->belongsTo(SalePost::class);
    }
}