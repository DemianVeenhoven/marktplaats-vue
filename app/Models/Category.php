<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// TODO :: Capitalize model
class category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    // TODO :: pluralize, cause one category has many ads
    public function ad()
    {
        return $this->belongsToMany(Ad::class);
    }
}
