<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
     protected $table = 'contacts';
    protected $fillable = [
        'user_one_id',
        'user_two_id',
        'sale_post_id' // THÊM DÒNG NÀY
    ];
     public function userOne()
    {
        return $this->belongsTo(User::class, 'user_one_id');
    }

    public function userTwo()
    {
        return $this->belongsTo(User::class, 'user_two_id');
    }

    public function messages()
    {
        return $this->hasMany(ContactMessage::class);
    }
    public function salePost()
    {
        // Giả sử bảng contacts có cột sale_post_id
        return $this->belongsTo(SalePost::class, 'sale_post_id');
    }
}