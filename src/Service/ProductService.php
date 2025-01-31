<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\ProductDTO;
use App\Entity\Product;
use App\Enum\CategoryEnum;
use App\Enum\UnitEnum;
use App\Factory\ProductFactory;
use App\Service\Storage\StorageInterface;

final readonly class ProductService
{
    public function __construct(
        private StorageInterface $storage,
        private UnitConversionService $unitConversionService,
    ) {
    }

    public function addProduct(ProductDTO $dto): void
    {
        $dto->quantity = UnitEnum::KILOGRAMS->value === $dto->unit
            ? $this->unitConversionService->convertToGrams($dto->quantity)
            : $dto->quantity;

        $product = ProductFactory::createFromDTO($dto);

        $this->storage->add($product);
    }

    public function listProducts(?CategoryEnum $category = null): array
    {
        return $category
            ? $this->storage->listAllByCategory(Product::class, $category->value)
            : $this->storage->listAll(Product::class)
        ;
    }

    public function removeProduct(string $id): void
    {
        $this->storage->remove($id, Product::class);
    }

    public function getProductById(string $id): ?ProductDTO
    {
        $productData = $this->storage->get($id, Product::class);

        if (!$productData) {
            return null;
        }

        return ProductDTO::fromArray($productData);
    }

    public function searchProducts(string $query): array
    {
        return $this->storage->search($query, Product::class);
    }
}
