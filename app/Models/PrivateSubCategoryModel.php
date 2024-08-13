<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrivateSubCategoryModel extends Model
{
    use HasFactory;
    public function category()
    {
        return $this->belongsTo(PrivateCategory::class, 'private_category_id');
    }

    public function childCategories()
    {
        return $this->hasMany(PrivateChildCategoryModel::class, 'private_sub_category_id');
    }

    public function private_products()
    {
        return $this->hasMany(Product::class, 'private_category_id');
    }

    protected $casts = [
        'private_category_id' => 'integer',
        'status' => 'integer'
    ];
}