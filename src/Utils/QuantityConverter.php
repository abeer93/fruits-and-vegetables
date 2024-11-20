<?php

namespace App\Utils;

class QuantityConverter
{
    public static function convertToGrams(int $quantity, string $unit): int
    {
        if ($unit === 'kg') {
            return $quantity * 1000;
        }

        return $quantity;
    }

    public static function convertToKiloGrams(int $quantity, string $unit): int
    {
        if ($unit === 'g') {
            return $quantity / 1000;
        }

        return $quantity;
    }
}
