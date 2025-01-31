<?php

declare(strict_types=1);

namespace App\Service\Storage;

use App\Enum\StorageTypeEnum;
use App\Exception\StorageTypeException;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

class StorageService
{
    private StorageInterface $storage;

    /**
     * @param iterable<StorageInterface> $storages
     *
     * @throws StorageTypeException
     */
    public function __construct(
        #[AutowireIterator(tag: 'app.storage')] private iterable $storages,
        private readonly StorageTypeEnum $storageType = StorageTypeEnum::REDIS,
    ) {
        foreach ($storages as $storage) {
            if ($storage->supports($this->storageType->value)) {
                $this->storage = $storage;

                return;
            }
        }

        throw new StorageTypeException($this->storageType->value);
    }

    public function getStorage(): StorageInterface
    {
        return $this->storage;
    }
}
