<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Bid;
use App\Models\MessageChain;

class Ad extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        "image",
        'premium'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
    
    public function bids() {
        return $this->hasMany(Bid::class);
    }

    public function messageChains() {
        return $this->hasMany(MessageChain::class);
    }
}
