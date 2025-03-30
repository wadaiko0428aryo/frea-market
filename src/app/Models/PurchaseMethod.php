<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_id',
        'purchase_method',
    ];
}
