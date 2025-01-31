<?php

declare(strict_types=1);

namespace App\Service\Storage\KeyProvider;

use App\Exception\InvalidRedisKeyException;

class RedisKeyService
{
    /**
     * @param RedisKeyProviderInterface[] $keyProviders
     */
    public function __construct(#[AutowireIterator(tag: 'app.redis_key_provider')] private iterable $keyProviders)
    {
    }

    public function getRedisKeyForEntity(string $entityType, ?string $category = null): string
    {
        foreach ($this->keyProviders as $provider) {
            if ($provider instanceof RedisKeyProviderInterface && $provider->supportsEntityType($entityType)) {
                return $provider->getRedisKeyForEntity($entityType, $category);
            }
        }

        throw new InvalidRedisKeyException($entityType);
    }
}
