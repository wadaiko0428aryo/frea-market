<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_id',
        'comment',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);  // Commentは1人のUserに属する
    }

    public function item()
    {
        return $this->belongsTo(Item::class);  // Commentは1つのItemに属する
    }
}