<?php

declare(strict_types=1);

namespace App\Service;

class UnitConversionService
{
    private const GRAMS_IN_KILOGRAM = 1000;

    public function convertToGrams(float $kg): float
    {
        return $kg * self::GRAMS_IN_KILOGRAM;
    }

    public function convertToKilograms(float $g): float
    {
        return $g / self::GRAMS_IN_KILOGRAM;
    }
}
