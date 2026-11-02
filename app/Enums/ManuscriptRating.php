<?php

namespace App\Enums;

enum ManuscriptRating: string
{
    case Excellent = 'excellent';
    case Promising = 'promising';
    case SomeWorkRequired = 'some_work_required';
    case Poor = 'poor';

    public function label(): string
    {
        return match ($this) {
            self::Excellent => 'Excellent',
            self::Promising => 'Promising',
            self::SomeWorkRequired => 'Some Work Required',
            self::Poor => 'Poor',
        };
    }
}

