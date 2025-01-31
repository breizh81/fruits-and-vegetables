<?php

declare(strict_types=1);

namespace App\Service\Storage\KeyProvider;

use App\Entity\Product;
use App\Enum\CategoryEnum;
use App\Enum\RedisKeyEnum;
use App\Exception\InvalidCategoryException;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('app.redis_key_provider')]
class ProductRedisKeyProvider implements RedisKeyProviderInterface
{
    public function supportsEntityType(string $entityType): bool
    {
        return Product::class === $entityType;
    }

    public function getRedisKeyForEntity(string $entityType, ?string $category = null): string
    {
        if (!$category) {
            $keys = [];
            foreach (CategoryEnum::cases() as $categoryEnum) {
                $keys[] = RedisKeyEnum::PRODUCTS->value.':'.$categoryEnum->value;
            }

            return implode(',', $keys);
        }

        if (CategoryEnum::tryFrom($category)) {
            return RedisKeyEnum::PRODUCTS->value.':'.$category;
        }

        throw new InvalidCategoryException($category);
    }
}
