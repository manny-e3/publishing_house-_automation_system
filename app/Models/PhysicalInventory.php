<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\BindingType;
use App\Traits\LogsAudit;

class PhysicalInventory extends Model
{
    use HasFactory, LogsAudit;

    protected $table = 'physical_inventory';

    protected $fillable = [
        'prospect_id',
        'format',
        'quantity_ordered',
        'quantity_in_stock',
        'warehouse_location',
        'reorder_threshold'
    ];

    protected $casts = [
        'format' => BindingType::class,
        'quantity_ordered' => 'integer',
        'quantity_in_stock' => 'integer',
        'reorder_threshold' => 'integer',
    ];

    public function prospect(): BelongsTo
    {
        return $this->belongsTo(Prospect::class);
    }

    public function isLowStock(): bool
    {
        return $this->quantity_in_stock <= $this->reorder_threshold;
    }
}
