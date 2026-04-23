<?php

namespace App\Models;

use App\Traits\LogsAudit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    use LogsAudit;
    protected $fillable = [
        'prospect_id', 
        'invoice_number', 
        'amount', 
        'status', 
        'payment_reference', 
        'paid_at',
        'allowed_gateways',
        'payment_plans',
        'min_deposit_percentage',
        'total_paid',
        'is_installment'
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'amount' => 'decimal:2',
        'allowed_gateways' => 'array',
        'payment_plans' => 'array',
    ];

    public function prospect(): BelongsTo
    {
        return $this->belongsTo(Prospect::class);
    }
}
