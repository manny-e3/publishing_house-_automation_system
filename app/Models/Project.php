<?php

namespace App\Models;

use App\Traits\LogsAudit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Project extends Model
{
    use LogsAudit;
    protected $fillable = [
        'prospect_id',
        'status',
        'current_stage',
        'payment_reference',
        'estimated_completion_date'
    ];

    const STAGES = [
        'project_recording' => 'Project Recording',
        'manuscript_review' => 'Manuscript Review',
        'book_cover_isbn' => 'Book Cover / ISBN Application',
        'editing' => 'Book Editing',
        'formatting' => 'Formatting',
        'dummy_review' => 'Dummy Copy Review',
        'printing' => 'Printing',
        'distribution' => 'Distribution',
        'sales_promotion' => 'Sales & Promotion',
        'reviews' => 'Final Reviews'
    ];

    public function getStageLabelAttribute()
    {
        return self::STAGES[$this->current_stage] ?? 'Unknown Stage';
    }

    public function getProgressPercentageAttribute()
    {
        $stageProgress = [
            'project_recording' => 10,
            'manuscript_review' => 20,
            'book_cover_isbn' => 30,
            'editing' => 40,
            'formatting' => 50,
            'dummy_review' => 60,
            'printing' => 75,
            'distribution' => 85,
            'sales_promotion' => 95,
            'reviews' => 100
        ];

        return $stageProgress[$this->current_stage] ?? 0;
    }


    protected $casts = [
        'estimated_completion_date' => 'date',
    ];

    public function prospect(): BelongsTo
    {
        return $this->belongsTo(Prospect::class);
    }

    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class, 'payment_reference', 'payment_reference');
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }

    public function latestContract()
    {
        return $this->hasOne(Contract::class)->latestOfMany();
    }
}
