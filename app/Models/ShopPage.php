<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopPage extends Model
{
    use HasFactory;

    protected $casts = [
        'status' => 'integer',
        'filter_price_range' => 'integer',
    ];
}
