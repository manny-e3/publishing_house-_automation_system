<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contract extends Model
{
    protected $fillable = [
        'project_id',
        'prospect_id',
        'contract_type',
        'status',
        'signed_at',
        'signature_info',
        'document_path',
    ];

    protected $casts = [
        'signed_at' => 'datetime',
        'signature_info' => 'array',
    ];

    /**
     * Get the prospect associated with the contract.
     */
    public function prospect(): BelongsTo
    {
        return $this->belongsTo(Prospect::class);
    }

    /**
     * Get the project associated with the contract.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
