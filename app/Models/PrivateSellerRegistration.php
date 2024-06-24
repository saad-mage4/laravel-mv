<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrivateSellerRegistration extends Model
{
    use HasFactory;

    protected $table = 'private_seller_registration';

    protected $fillable = [
        'email',
        'first_name',
        'last_name',
        'phone_no',
    ];
}
