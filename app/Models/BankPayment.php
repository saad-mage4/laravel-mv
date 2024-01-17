<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankPayment extends Model
{
    use HasFactory;
    protected $casts = [
        'status' => 'integer',
        'cash_on_delivery_status' => 'integer',
    ];
}
