<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShoppingCart extends Model
{
    use HasFactory;

    public function variants(){
        return $this->hasMany(ShoppingCartVariant::class, 'shopping_cart_id');
    }

    protected $casts = [
        'user_id' => 'integer',
        'product_id' => 'integer',
        'qty' => 'integer',
        'price' => 'integer',
        'tax' => 'integer',
        'coupon_price' => 'integer',
        'offer_type' => 'integer',
    ];
}
