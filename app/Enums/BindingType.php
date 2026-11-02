<?php

namespace App\Enums;

enum BindingType: string
{
    case Paperback = 'paperback';
    case Hardcover = 'hardcover';

    public function label(): string
    {
        return match ($this) {
            self::Paperback => 'Paperback',
            self::Hardcover => 'Hardcover',
        };
    }
}

