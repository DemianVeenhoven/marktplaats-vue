<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Bid;

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

    public function category()
    {
        return $this->belongsToMany(Category::class);
    }
    
    public function bid() {
        return $this->hasMany(Bid::class);
    }
}
