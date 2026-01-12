<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
         'name',
        'email',
        'password',
        'status',
        'role',
        'profile_picture',
        'phone_number'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
     public function adminActions()
    {
        return $this->hasMany(AdminAction::class, 'admin_id');
    }

    public function salePosts()
    {
        return $this->hasMany(SalePost::class);
    }

    public function salePostChats()
    {
        return $this->hasMany(SalePostChat::class);
    }

    public function contactsAsUserOne()
    {
        return $this->hasMany(Contact::class, 'user_one_id');
    }

    public function contactsAsUserTwo()
    {
        return $this->hasMany(Contact::class, 'user_two_id');
    }

    public function contactMessages()
    {
        return $this->hasMany(ContactMessage::class, 'sender_id');
    }

    public function news()
    {
        return $this->hasMany(News::class, 'author_id');
    }
}
