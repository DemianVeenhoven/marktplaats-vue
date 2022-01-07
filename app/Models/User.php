<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Ad;
use App\Models\Bid;
use App\Models\MessageChain;
use App\Models\Message;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        "getMessageNotifications",
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function postalCode()
    {
        return $this->belongsToMany(PostalCode::class);
    }

    public function ad() {
        return $this->hasMany(Ad::class);
    }

    public function bid() {
        return $this->hasMany(Bid::class);
    }

    public function messageChain() {
        return $this->hasMany(MessageChain::class);
    }

    public function message() {
        return $this->hasMany(Message::class);
    }
}
