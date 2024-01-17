<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    use HasFactory;

    public function seller(){
        return $this->belongsTo(Vendor::class,'seller_id');
    }

    public function orderProductVariants(){
        return $this->hasMany(OrderProductVariant::class);
    }

    public function order(){
        return $this->belongsTo(Order::class);
    }

    public function product(){
        return $this->belongsTo(Product::class)->select(['id','thumb_image','slug']);
    }

    protected $casts = [
        'order_id' => 'integer',
        'product_id' => 'integer',
        'seller_id' => 'integer',
        'unit_price' => 'integer',
        'vat' => 'integer',
        'qty' => 'integer',
    ];

}
