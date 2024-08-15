<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdType extends Model
{
    use HasFactory;

    public function private_products()
    {
        return $this->hasMany(Product::class, 'private_category_id');
    }

    protected $casts = [
        'status' => 'integer',
    ];
}
