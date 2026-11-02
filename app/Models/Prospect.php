<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\PublishingModel;
use App\Enums\ManuscriptRating;
use App\Enums\DesignStatus;
use App\Enums\WorkflowStatus;

class Prospect extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'preferred_communication' => 'array',
        'date_of_birth' => 'date',
        'default_services' => 'array',
        'quote_for_services' => 'array',
        'printing_quote' => 'array',
        'bespoke_marketing' => 'array',
        'preferred_sales_channels' => 'array',
        'agreement_terms' => 'boolean',
        
        // Master Workflow Casts
        'determined_model' => PublishingModel::class,
        'manuscript_rating' => ManuscriptRating::class,
        'design_phase' => DesignStatus::class,
        'has_social_presence' => 'boolean',
        'is_established_author' => 'boolean',
        'checklist_progress' => 'array',
        'metadata_file' => 'array',
        'total_calculated_fee' => 'decimal:2',
        'total_bulk_print_estimate' => 'decimal:2',
        'requested_print_quantity' => 'integer',
        'author_age' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function evaluations(): HasMany
    {
        return $this->hasMany(ProspectEvaluation::class);
    }

    public function project(): HasOne
    {
        return $this->hasOne(Project::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Ensure payment status reflects any paid invoices.
     */
    public function getPaymentStatusAttribute($value): string
    {
        if ($value === 'paid') {
            return 'paid';
        }

        if ($this->relationLoaded('invoices')) {
            if ($this->invoices->where('status', 'paid')->isNotEmpty()) {
                return 'paid';
            }
        } elseif ($this->invoices()->where('status', 'paid')->exists()) {
            return 'paid';
        }

        return $value ?: 'unpaid';
    }

    /**
     * Get the currency code for this prospect (USD or NGN).
     */
    public function getCurrencyAttribute(): string
    {
        $quoteCurrency = $this->metadata_file['pricing_quote']['currency'] ?? null;
        if (!empty($quoteCurrency)) {
            return strtoupper($quoteCurrency);
        }

        if (($this->payment_merchant ?? '') === 'stripe') {
            return 'USD';
        }

        return 'NGN';
    }

    /**
     * Get the currency symbol for this prospect ($ or ₦).
     */
    public function getCurrencySymbolAttribute(): string
    {
        return $this->currency === 'USD' ? '$' : '₦';
    }

    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class);
    }

    public function channelListings(): HasMany
    {
        return $this->hasMany(ChannelListing::class);
    }

    public function physicalInventory(): HasMany
    {
        return $this->hasMany(PhysicalInventory::class);
    }

    public function isOsisiHybrid(): bool
    {
        return $this->determined_model === PublishingModel::HybridOsisi;
    }

    public function isTraditional(): bool
    {
        return $this->determined_model === PublishingModel::Traditional;
    }

    /**
     * Check if a specific checklist task is completed
     */
    public function isChecklistComplete(string $taskKey): bool
    {
        $progress = $this->checklist_progress ?? [];
        return !empty($progress[$taskKey]['completed']);
    }

    /**
     * Mark a checklist task as completed or incomplete with audit trail
     */
    public function updateChecklist(string $taskKey, bool $completed, int $userId): void
    {
        $progress = $this->checklist_progress ?? [];
        $progress[$taskKey] = [
            'completed' => $completed,
            'timestamp' => now()->toISOString(),
            'user_id' => $userId,
        ];

        $metadata = $this->metadata_file ?? [];
        $metadata['checklist_history'][] = [
            'task' => $taskKey,
            'completed' => $completed,
            'timestamp' => now()->toISOString(),
            'user_id' => $userId,
        ];

        $this->update([
            'checklist_progress' => $progress,
            'metadata_file' => $metadata
        ]);
    }
}
