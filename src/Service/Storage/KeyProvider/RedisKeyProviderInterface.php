<?php

declare(strict_types=1);

namespace App\Service\Storage\KeyProvider;

interface RedisKeyProviderInterface
{
    public function supportsEntityType(string $entityType): bool;

    public function getRedisKeyForEntity(string $entityType, ?string $category = null): string;
}
