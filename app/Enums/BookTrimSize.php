<?php

namespace App\Enums;

enum BookTrimSize: string
{
    case FiveByEight = 'five_by_eight'; // 5x8
    case SixByNine = 'six_by_nine';     // 6x9
    case SevenByTen = 'seven_by_ten';   // 7x10

    public function label(): string
    {
        return match ($this) {
            self::FiveByEight => '5" x 8" (Pocket / Standard)',
            self::SixByNine => '6" x 9" (Trade Paperback)',
            self::SevenByTen => '7" x 10" (Executive / Text)',
        };
    }

    public function wordsPerPage(): int
    {
        return match ($this) {
            self::FiveByEight => 300,
            self::SixByNine => 350,
            self::SevenByTen => 400,
        };
    }
}

