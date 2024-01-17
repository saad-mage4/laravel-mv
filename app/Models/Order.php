<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;


    public function user(){
        return $this->belongsTo(User::class);
    }

    public function orderProducts(){
        return $this->hasMany(OrderProduct::class);
    }

    public function orderAddress(){
        return $this->hasOne(OrderAddress::class);
    }

    protected $casts = [
        'amount_usd' => 'integer',
        'sub_total' => 'integer',
        'amount_real_currency' => 'integer',
        'currency_rate' => 'integer',
        'product_qty' => 'integer',
        'payment_status' => 'integer',
        'refound_status' => 'integer',
        'shipping_cost' => 'integer',
        'coupon_coast' => 'integer',
        'order_vat' => 'integer',
        'order_status' => 'integer',
        'cash_on_delivery' => 'integer'
    ];
}
