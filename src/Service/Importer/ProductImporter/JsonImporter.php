<?php

declare(strict_types=1);

namespace App\Service\Importer\ProductImporter;

use App\DTO\ProductDTO;
use App\Enum\UnitEnum;
use App\Exception\InvalidJsonFormatException;
use App\Exception\JsonFileImportException;
use App\Service\ProductService;
use App\Service\UnitConversionService;

final readonly class JsonImporter
{
    public function __construct(
        private ProductService $productService,
        private UnitConversionService $unitConversionService,
        private string $importFilePath,
    ) {
    }

    public function import(): int
    {
        try {
            if (!file_exists($this->importFilePath)) {
                throw new JsonFileImportException();
            }

            $data = json_decode(file_get_contents($this->importFilePath), true);

            if (!$data) {
                throw new InvalidJsonFormatException();
            }

            $importedCount = 0;
            foreach ($data as $item) {
                try {
                    $item['category'] = $item['type'];
                    $item['quantity'] = $this->convertQuantity($item['unit'], $item['quantity']);

                    $dto = ProductDTO::fromArray($item);

                    $this->productService->addProduct($dto);
                    ++$importedCount;
                } catch (\Exception $e) {
                    continue;
                }
            }

            return $importedCount;
        } catch (\Exception $e) {
            throw new JsonFileImportException($e->getMessage());
        }
    }

    private function convertQuantity(string $unit, float|int $quantity): float|int
    {
        return UnitEnum::KILOGRAMS->value === $unit
            ? $this->unitConversionService->convertToGrams($quantity)
            : $quantity;
    }
}
