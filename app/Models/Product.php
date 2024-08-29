<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $appends = ['averageRating'];



    public function getAverageRatingAttribute()
    {
        return $this->avgReview()->avg('rating') ?: '0';
    }


    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function private_categories()
    {
        return $this->belongsTo(PrivateCategory::class);
    }

    public function wholesales()
    {
        return $this->hasMany(Wholesell::class)->orderBy('minimum_product', 'asc');
    }

    public function seller()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    // for user
    // public function user()
    // {
    //     return $this->belongsTo(User::class, 'vendor_id');
    // }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function gallery()
    {
        return $this->hasMany(ProductGallery::class);
    }

    public function specifications()
    {
        return $this->hasMany(ProductSpecification::class);
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }


    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function activeVariants()
    {

        return $this->hasMany(ProductVariant::class)->select(['id', 'name', 'product_id']);
    }



    public function returnPolicy()
    {
        return $this->belongsTo(ReturnPolicy::class);
    }

    public function tax()
    {
        return $this->belongsTo(ProductTax::class);
    }

    public function variantItems()
    {
        return $this->hasMany(ProductVariantItem::class);
    }


    public function activeReview()
    {
        return $this->hasMany(ProductReview::class)->where('status', 1);
    }

    public function avgReview()
    {
        return $this->hasMany(ProductReview::class)->where('status', 1);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }


    protected $casts = [
        'vendor_id' => 'integer',
        'category_id' => 'integer',
        'sub_category_id' => 'integer',
        'child_category_id' => 'integer',
        'brand_id' => 'integer',
        'qty' => 'integer',
        'price' => 'integer',
        'offer_price' => 'integer',
        'tax_id' => 'integer',
        'is_cash_delivery' => 'integer',
        'is_return' => 'integer',
        'return_policy_id' => 'integer',
        'is_warranty' => 'integer',
        'show_homepage' => 'integer',
        'is_undefine' => 'integer',
        'is_featured' => 'integer',
        'is_wholesale' => 'integer',
        'new_product' => 'integer',
        'is_top' => 'integer',
        'is_best' => 'integer',
        'is_flash_deal' => 'integer',
        'buyone_getone' => 'integer',
        'status' => 'integer',
        'is_specification' => 'integer',
    ];
}