<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrivateCategory extends Model
{
    use HasFactory;

    protected $table = 'private_categories';


    public function private_subCategories()
    {
        return $this->hasMany(PrivateSubCategoryModel::class);
    }

    public function private_products()
    {
        return $this->hasMany(Product::class, 'private_category_id');
    }

    protected $casts = [
        'status' => 'integer'
    ];
}