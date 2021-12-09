<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Message;

class MessageChain extends Model
{
    use HasFactory;

    public function ad()
    {
        return $this->belongsTo(Ad::class);
    }

    public function bidder()
    {
        return $this->belongsTo(User::class);
    }
    
    public function message() {
        return $this->hasMany(Message::class);
    }
}
