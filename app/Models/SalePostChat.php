<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalePostChat extends Model
{
    protected $table= 'sale_post_chats';
    protected $fillable = [
        'sale_post_id',
        'user_id',
        'message',
        'parent_id'
    ];
    public function salePost()
    {
        return $this->belongsTo(SalePost::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(SalePostChat::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(SalePostChat::class, 'parent_id');
    }
}
