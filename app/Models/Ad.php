<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Bid;
use App\Models\MessageChain;

// TODO :: Capitalize model
class ad extends Model
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

    // TODO :: pluralize, cause one ad has many categories
    public function category()
    {
        return $this->belongsToMany(Category::class);
    }

    // TODO :: pluralize, cause one ad has many bids
    public function bid() {
        return $this->hasMany(Bid::class);
    }

    // TODO :: pluralize, cause one ad has many messageChains
    public function messageChain() {
        return $this->hasMany(MessageChain::class);
    }
}
