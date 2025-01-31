<?php

declare(strict_types=1);

namespace App\Service\Storage;

interface StorageInterface
{
    public function add(object $entity): void;

    public function get(string $id, string $entityType): array;

    public function remove(string $id, string $entityType): void;

    public function listAll(string $entityType): array;

    public function listAllByCategory(string $entityType, string $category): array;

    public function supports(string $storageType): bool;
}
