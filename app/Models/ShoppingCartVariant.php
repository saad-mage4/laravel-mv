<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShoppingCartVariant extends Model
{
    use HasFactory;


    protected $casts = [
        'shopping_cart_id' => 'integer',
        'price' => 'integer',
    ];
}
