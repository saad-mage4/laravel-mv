<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariantItem extends Model
{
    use HasFactory;

    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function variant(){
        return $this->belongsTo(ProductVariant::class,'product_variant_id');
    }

    protected $casts = [
        'product_variant_id' => 'integer',
        'product_id' => 'integer',
        'price' => 'integer',
        'status' => 'integer',
        'is_default' => 'integer'
    ];

}
