<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PopularCategory extends Model
{
    use HasFactory;

    protected $casts = [
        'product_qty' => 'integer',
        'category_id_one' => 'integer',
        'sub_category_id_one' => 'integer',
        'child_category_id_one' => 'integer',
        'category_id_two' => 'integer',
        'sub_category_id_two' => 'integer',
        'child_category_id_two' => 'integer',
        'category_id_three' => 'integer',
        'sub_category_id_three' => 'integer',
        'child_category_id_three' => 'integer',
        'category_id_four' => 'integer',
        'sub_category_id_four' => 'integer',
        'child_category_id_four' => 'integer',
    ];
}
