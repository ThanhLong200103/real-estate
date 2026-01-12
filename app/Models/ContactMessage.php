<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
     protected $table = 'contact_messages';
     protected $fillable = [
        'contact_id',
        'sender_id',
        'message'
     ];
     public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
