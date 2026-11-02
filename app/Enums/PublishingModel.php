<?php

namespace App\Enums;

enum PublishingModel: string
{
    case Traditional = 'traditional';
    case HybridOsisi = 'hybrid_osisi';

    public function label(): string
    {
        return match ($this) {
            self::Traditional => 'Traditional Publishing',
            self::HybridOsisi => 'Osisi Hybrid Model',
        };
    }
}

