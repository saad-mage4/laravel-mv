<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaystackAndMollie extends Model
{
    use HasFactory;

    protected $casts = [
        'mollie_status' => 'integer',
        'mollie_currency_rate' => 'integer',
        'paystack_status' => 'integer',
        'paystack_currency_rate' => 'integer',
    ];
}
