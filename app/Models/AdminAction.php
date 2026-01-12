<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminAction extends Model
{
    //
    protected $table = 'admin_actions';
    protected $fillable = [
        'admin_id',
        'action_type',
        'description',
        'action_time'
    ];
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
