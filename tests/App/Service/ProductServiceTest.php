<?php

declare(strict_types=1);

namespace App\Tests\App\Service;

use App\DTO\ProductDTO;
use App\Entity\Product;
use App\Service\ProductService;
use App\Service\Storage\StorageInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ProductServiceTest extends TestCase
{
    private MockObject $storageMock;
    private ProductService $productService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->storageMock = $this->createMock(StorageInterface::class);

        $this->productService = new ProductService($this->storageMock);
    }

    public function testListProducts(): void
    {
        $this->storageMock->expects($this->once())
            ->method('listAll')
            ->with(Product::class)
            ->willReturn([/* some products */]);

        $products = $this->productService->listProducts();

        $this->assertIsArray($products);
    }

    public function testRemoveProduct(): void
    {
        $productId = 'some-id';

        $this->storageMock->expects($this->once())
            ->method('remove')
            ->with($productId, Product::class);

        $this->productService->removeProduct($productId);
    }

    public function testGetProductByIdReturnsProductDTO(): void
    {
        $productId = 'some-id';

        $this->storageMock->expects($this->once())
            ->method('get')
            ->with($productId, Product::class)
            ->willReturn([
                'id' => $productId,
                'name' => 'Test Product',
                'quantity' => 10,
                'category' => 'fruit',
                'unit' => 'kg',
            ]);

        $productDTO = $this->productService->getProductById($productId);

        $this->assertInstanceOf(ProductDTO::class, $productDTO);
    }

    public function testGetProductByIdReturnsNullWhenProductNotFound(): void
    {
        $productId = 'non-existent-id';

        $this->storageMock->expects($this->once())
            ->method('get')
            ->with($productId, Product::class)
            ->willReturn([]);

        $productDTO = $this->productService->getProductById($productId);

        $this->assertNull($productDTO);
    }
}
