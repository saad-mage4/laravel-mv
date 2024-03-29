<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MegaMenuSubCategory extends Model
{
    use HasFactory;

    public function subCategory(){
        return $this->belongsTo(SubCategory::class);
    }

    public function megaMenuCategory(){
        return $this->belongsTo(MegaMenuCategory::class);
    }

    protected $casts = [
        'status' => 'integer',
        'mega_menu_category_id' => 'integer',
        'sub_category_id' => 'integer'
    ];

}
