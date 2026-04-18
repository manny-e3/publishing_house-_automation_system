<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentGateway extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'config',
        'is_active'
    ];

    protected $casts = [
        'config' => 'array',
        'is_active' => 'boolean'
    ];
}
