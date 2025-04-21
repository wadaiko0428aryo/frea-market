<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_id',
        'profile_id',
        'purchase_method',
    ];

    public function user()
    {
        return $this->belongsToMany(User::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id'); 
    }

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }
}
