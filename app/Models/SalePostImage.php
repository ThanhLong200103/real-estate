<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalePostImage extends Model
{
    protected $table = 'sale_post_images';
    protected $fillable = [
        'sale_post_id',
        'image_url'
    ];
    public function salePost()
    {
        return $this->belongsTo(SalePost::class);
    }
}
