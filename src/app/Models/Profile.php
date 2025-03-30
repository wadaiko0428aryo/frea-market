<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'user_id',
        'post',
        'address',
        'building',
    ];

    public function purchase()
    {
        return $this->belongsToMany(Purchase::class);
    }
}
