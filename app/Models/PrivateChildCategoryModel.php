<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrivateChildCategoryModel extends Model
{
    use HasFactory;

    public function private_subCategories()
    {
        return $this->belongsTo(PrivateSubCategoryModel::class, 'private_sub_category_id');
    }

    public function private_category()
    {
        return $this->belongsTo(PrivateCategory::class, 'private_category_id');
    }

    public function private_products()
    {
        return $this->hasMany(Product::class, 'private_category_id');
    }

    protected $casts = [
        'status' => 'integer',
        'private_category_id' => 'integer',
        'private_sub_category_id' => 'integer'
    ];
}