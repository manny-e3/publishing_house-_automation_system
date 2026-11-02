<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\DistributionChannel;
use App\Traits\LogsAudit;

class ChannelListing extends Model
{
    use HasFactory, LogsAudit;

    protected $fillable = [
        'prospect_id',
        'channel',
        'storefront_url',
        'is_live',
        'verified_by_user_id',
        'live_at'
    ];

    protected $casts = [
        'channel' => DistributionChannel::class,
        'is_live' => 'boolean',
        'live_at' => 'datetime'
    ];

    public function prospect(): BelongsTo
    {
        return $this->belongsTo(Prospect::class);
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by_user_id');
    }
}
