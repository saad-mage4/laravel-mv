<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MegaMenuCategory extends Model
{
    use HasFactory;

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function subCategories(){
        return $this->hasMany(MegaMenuSubCategory::class);
    }

    protected $casts = [
        'status' => 'integer',
        'category_id' => 'integer'
    ];

}
