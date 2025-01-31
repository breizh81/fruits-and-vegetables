<?php

declare(strict_types=1);

namespace App\Factory;

use App\DTO\ProductDTO;
use App\Entity\Product;

class ProductFactory
{
    public static function createFromDTO(ProductDTO $dto): Product
    {
        return new Product(
            $dto->name,
            $dto->quantity,
            $dto->category
        );
    }
}
