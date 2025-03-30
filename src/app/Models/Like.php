<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_id',
    ];

    // 各Likeは1つのUserに所属
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 各Likeは1つのItemに所属
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}