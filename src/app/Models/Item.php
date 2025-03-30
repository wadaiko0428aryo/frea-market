<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'price',
        'description',
        'image',
        'condition',
        'brand',
        'is_sold',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_item');
    }

    public function purchase()
    {
        return $this->hasMany(Purchase::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    // ユーザーがこの商品を「いいね」しているかどうかを判定するメソッド
    public function isLikedByUser()
    {
        return $this->likes()->where('user_id', auth()->id())->exists();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
