<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
     protected $table='news';
    protected $fillable = [
        'author_id',
        'title',
        'description',
        'status'
    ];
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function images()
    {
        return $this->hasMany(NewsImage::class);
    }
}
