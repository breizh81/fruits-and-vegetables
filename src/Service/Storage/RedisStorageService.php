<?php

declare(strict_types=1);

namespace App\Service\Storage;

use App\Enum\StorageTypeEnum;
use App\Factory\EntitySerializer;
use App\Service\Storage\KeyProvider\RedisKeyService;
use Predis\Client;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('app.storage')]
final readonly class RedisStorageService implements StorageInterface
{
    public function __construct(
        private Client $redis,
        private RedisKeyService $redisKeyService,
        private EntitySerializer $entitySerializer,
    ) {
    }

    public function add(object $entity): void
    {
        $redisKey = $this->redisKeyService->getRedisKeyForEntity($entity::class, $entity->getCategory());
        $this->redis->hset($redisKey, $entity->getId(), json_encode($this->entitySerializer->toArray($entity)));
    }

    public function get(string $id, string $entityType): array
    {
        $redisKey = $this->redisKeyService->getRedisKeyForEntity($entityType);
        $keys = explode(',', $redisKey);

        foreach ($keys as $key) {
            $entity = $this->redis->hget($key, $id);

            if (null !== $entity) {
                return json_decode($entity, true);
            }
        }

        return [];
    }

    public function remove(string $id, string $entityType): void
    {
        $redisKey = $this->redisKeyService->getRedisKeyForEntity($entityType);
        $keys = explode(',', $redisKey);
        foreach ($keys as $key) {
            $this->redis->hdel($key, [$id]);
        }
    }

    public function listAll(string $entityType): array
    {
        $redisKey = $this->redisKeyService->getRedisKeyForEntity($entityType);

        $keys = explode(',', $redisKey);
        $results = [];

        foreach ($keys as $key) {
            $entities = $this->redis->hgetall($key);

            $results = array_merge($results, array_map(fn ($e) => json_decode($e, true), $entities));
        }

        return  $results;
    }

    public function listAllByCategory(string $entityType, string $category): array
    {
        $redisKey = $this->redisKeyService->getRedisKeyForEntity($entityType, $category);
        $entities = $this->redis->hgetall($redisKey);

        return array_map(fn ($e) => json_decode($e, true), $entities);
    }

    public function search(string $query, string $entityType): array
    {
        $redisKey = $this->redisKeyService->getRedisKeyForEntity($entityType);
        $keys = explode(',', $redisKey);
        $results = [];

        foreach ($keys as $key) {
            $entities = $this->redis->hgetall($key);

            foreach ($entities as $entity) {
                $entityArray = json_decode($entity, true);
                if (false !== stripos($entityArray['name'], $query)) {
                    $results[] = $entityArray;
                }
            }
        }

        return $results;
    }

    public function supports(string $storageType): bool
    {
        return StorageTypeEnum::REDIS->value === $storageType;
    }
}
