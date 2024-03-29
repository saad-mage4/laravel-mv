<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignProduct extends Model
{
    use HasFactory;

    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function campaign(){
        return $this->belongsTo(Campaign::class);
    }

    protected $casts = [
        'status' => 'integer',
        'campaign_id' => 'integer',
        'product_id' => 'integer',
        'show_homepage' => 'integer'
    ];


}
