<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\ProductDTO;
use App\Enum\CategoryEnum;
use App\Service\Importer\ProductImporter\JsonImporter;
use App\Service\ProductService;
use App\Validator\ProductValidator;
use App\Validator\ValidateInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    public function __construct(
        private readonly JsonImporter $jsonImporter,
        private readonly ProductService $productService,
        #[Autowire(service: ProductValidator::class)]
        private readonly ValidateInterface $productValidator,
    ) {
    }

    #[Route('/import', methods: ['POST'])]
    public function importProducts(): JsonResponse
    {
        try {
            $importedCount = $this->jsonImporter->import();

            return new JsonResponse(['message' => "Imported $importedCount products"], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], $e->getStatusCode());
        }
    }

    #[Route('/products', methods: ['GET'])]
    public function list(Request $request): JsonResponse
    {
        $category = $request->query->get('category');
        $categoryEnum = $category ? CategoryEnum::tryFrom($category) : null;

        if ($category && !$categoryEnum) {
            return new JsonResponse(['error' => 'Invalid category'], Response::HTTP_BAD_REQUEST);
        }

        $products = $this->productService->listProducts($categoryEnum);

        return $this->json($products);
    }

    #[Route('/products', methods: ['POST'])]
    public function add(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            $dto = ProductDTO::fromArray($data);

            $violations = $this->productValidator->validate($dto);

            if (!empty($violations)) {
                return new JsonResponse(['errors' => $violations], Response::HTTP_BAD_REQUEST);
            }

            $this->productService->addProduct($dto);

            return new JsonResponse(['message' => 'Product added'], Response::HTTP_CREATED);
        } catch (HttpExceptionInterface $e) {
            return new JsonResponse(['error' => $e->getMessage()], $e->getStatusCode());
        }
    }

    #[Route('/products/{id}', methods: ['DELETE'])]
    public function remove(string $id): JsonResponse
    {
        $this->productService->removeProduct($id);

        return new JsonResponse(['message' => 'Product removed'], Response::HTTP_OK);
    }

    #[Route('/products/search', methods: ['GET'])]
    public function search(Request $request): JsonResponse
    {
        $query = $request->query->get('query');

        if (!$query) {
            return new JsonResponse(['error' => 'Query parameter is required'], Response::HTTP_BAD_REQUEST);
        }

        $products = $this->productService->searchProducts($query);

        return $this->json($products);
    }

    #[Route('/products/{id}', methods: ['GET'])]
    public function getProductById(string $id): JsonResponse
    {
        $product = $this->productService->getProductById($id);

        if (!$product) {
            return new JsonResponse(['error' => 'Product not found'], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($product, Response::HTTP_OK);
    }
}
