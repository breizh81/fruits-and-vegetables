<?php

declare(strict_types=1);

namespace App\Tests\App\Service\Storage;

use App\Enum\StorageTypeEnum;
use App\Exception\StorageTypeException;
use App\Service\Storage\StorageInterface;
use App\Service\Storage\StorageService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class StorageServiceTest extends TestCase
{
    private MockObject $redisStorage;
    private StorageService $storageService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->redisStorage = $this->createMock(StorageInterface::class);

        $this->redisStorage->method('supports')
            ->willReturn(true);

        $storages = [$this->redisStorage];

        $this->storageService = new StorageService(
            $storages,
            StorageTypeEnum::REDIS
        );
    }

    public function testConstructorShouldSelectCorrectStorage(): void
    {
        $this->assertSame($this->redisStorage, $this->storageService->getStorage());
    }

    public function testConstructorShouldThrowExceptionIfNoMatchingStorage(): void
    {
        $unsupportedStorage = $this->createMock(StorageInterface::class);
        $unsupportedStorage->method('supports')
            ->willReturn(false);

        $storages = [$unsupportedStorage];

        $this->expectException(StorageTypeException::class);
        $this->expectExceptionMessage(StorageTypeEnum::REDIS->value);

        new StorageService($storages, StorageTypeEnum::REDIS);
    }

    public function testGetStorageReturnsCorrectStorage(): void
    {
        $this->assertSame($this->redisStorage, $this->storageService->getStorage());
    }
}
