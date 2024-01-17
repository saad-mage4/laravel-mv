<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $casts = [
        'maintenance_mode' => 'integer',
        'enable_user_register' => 'integer',
        'enable_multivendor' => 'integer',
        'enable_subscription_notify' => 'integer',
        'enable_save_contact_message' => 'integer',
    ];
}
