<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PricingRate extends Model
{
    protected $fillable = ['key', 'label', 'value', 'category'];
}
