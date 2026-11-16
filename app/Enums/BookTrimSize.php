<?php

namespace App\Enums;

enum BookTrimSize: string
{
    case FiveByEight = 'five_by_eight';                   // 5x8
    case FiveHalfByEightHalf = 'five_half_by_eight_half'; // 5.5x8.5
    case SixByNine = 'six_by_nine';                       // 6x9
    case Quarto = 'quarto';                               // Quarto / 8x10
    case SevenByTen = 'seven_by_ten';                     // 7x10

    public function label(): string
    {
        return match ($this) {
            self::FiveByEight => '5" x 8" Pocket / Poetry / Novella',
            self::FiveHalfByEightHalf => '5.5" x 8.5" Standard Fiction / Digest',
            self::SixByNine => '6" x 9" Trade Paperback / Non-Fiction',
            self::Quarto => 'Quarto / 8" x 10" Illustrated & Children\'s',
            self::SevenByTen => '7" x 10" Executive / Academic',
        };
    }

    public function wordsPerPage(): int
    {
        return match ($this) {
            self::FiveByEight => 300,
            self::FiveHalfByEightHalf => 320,
            self::SixByNine => 350,
            self::Quarto => 450,
            self::SevenByTen => 400,
        };
    }

    public function costMultiplier(): float
    {
        return match ($this) {
            self::FiveByEight => 1.0,
            self::FiveHalfByEightHalf => 1.05,
            self::SixByNine => 1.15,
            self::Quarto => 1.45,
            self::SevenByTen => 1.25,
        };
    }
}


