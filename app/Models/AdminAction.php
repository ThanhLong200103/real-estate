<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminAction extends Model
{
    protected $table = 'admin_actions';

    protected $fillable = [
        'admin_id',
        'action_type',
        'target_id',
        'target_type', // Thêm dòng này
        'description',
        'action_time'
    ];

    // Tự động gán thời gian hiện tại
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->action_time = now();
        });
    }

    public $timestamps = true;

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}