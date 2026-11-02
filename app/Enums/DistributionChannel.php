<?php

namespace App\Enums;

enum DistributionChannel: string
{
    case AmazonKdp = 'amazon_kdp';
    case Selar = 'selar';
    case AfricanBookCollective = 'african_book_collective';
    case IngramSpark = 'ingram_spark';

    public function label(): string
    {
        return match ($this) {
            self::AmazonKdp => 'Amazon KDP',
            self::Selar => 'Selar',
            self::AfricanBookCollective => 'African Book Collective',
            self::IngramSpark => 'IngramSpark',
        };
    }
}

