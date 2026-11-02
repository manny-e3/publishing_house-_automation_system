<?php

namespace App\Models;

use App\Traits\LogsAudit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get the currency code for this invoice (USD or NGN).
     */
    public function getCurrencyAttribute(): string
    {
        // 1. Check associated transactions
        $tx = $this->transactions()->where('status', 'successful')->latest()->first()
            ?? $this->transactions()->latest()->first();
        if ($tx) {
            if ($tx->gateway_slug === 'stripe') {
                return 'USD';
            }
            if (!empty($tx->currency) && strtoupper($tx->currency) === 'USD') {
                return 'USD';
            }
        }

        // 2. Check prospect pricing quote
        $quoteCurrency = $this->prospect?->metadata_file['pricing_quote']['currency'] ?? null;
        if (!empty($quoteCurrency)) {
            return strtoupper($quoteCurrency);
        }

        // 3. Check prospect payment merchant
        if (($this->prospect?->payment_merchant ?? '') === 'stripe') {
            return 'USD';
        }

        return 'NGN';
    }

    /**
     * Get the currency symbol for this invoice ($ or ₦).
     */
    public function getCurrencySymbolAttribute(): string
    {
        return $this->currency === 'USD' ? '$' : '₦';
    }
}
