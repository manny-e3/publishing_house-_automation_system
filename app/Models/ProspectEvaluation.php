<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProspectEvaluation extends Model
{
    protected $fillable = ['prospect_id', 'review_criterion_id', 'passed', 'notes'];

    public function criterion()
    {
        return $this->belongsTo(ReviewCriterion::class, 'review_criterion_id');
    }
}
