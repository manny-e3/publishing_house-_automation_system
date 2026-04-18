<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReviewCriterion extends Model
{
    protected $table = 'review_criteria';
    protected $fillable = ['label', 'description', 'is_active', 'sort_order'];
}
