<?php

declare(strict_types=1);

namespace App\Tests\App\Service;

use App\Service\UnitConversionService;
use PHPUnit\Framework\TestCase;

class UnitConversionServiceTest extends TestCase
{
    private UnitConversionService $unitConversionService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->unitConversionService = new UnitConversionService();
    }

    public function testConvertToGrams(): void
    {
        $kg = 2.5;
        $expectedGrams = 2500;

        $result = $this->unitConversionService->convertToGrams($kg);

        $this->assertEquals($expectedGrams, $result);
    }

    public function testConvertToKilograms(): void
    {
        $grams = 5000;
        $expectedKilograms = 5;

        $result = $this->unitConversionService->convertToKilograms($grams);

        $this->assertEquals($expectedKilograms, $result);
    }

    public function testConvertToGramsWithZero(): void
    {
        $kg = 0;
        $expectedGrams = 0;

        $result = $this->unitConversionService->convertToGrams($kg);

        $this->assertEquals($expectedGrams, $result);
    }

    public function testConvertToKilogramsWithZero(): void
    {
        $grams = 0;
        $expectedKilograms = 0;

        $result = $this->unitConversionService->convertToKilograms($grams);

        $this->assertEquals($expectedKilograms, $result);
    }

    public function testConvertToGramsWithNegative(): void
    {
        $kg = -2;
        $expectedGrams = -2000;

        $result = $this->unitConversionService->convertToGrams($kg);

        $this->assertEquals($expectedGrams, $result);
    }

    public function testConvertToKilogramsWithNegative(): void
    {
        $grams = -5000;
        $expectedKilograms = -5;

        $result = $this->unitConversionService->convertToKilograms($grams);

        $this->assertEquals($expectedKilograms, $result);
    }
}
