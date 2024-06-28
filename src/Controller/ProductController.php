<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Service\ProductService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class ProductController extends BaseController
{
    public function __construct(private readonly ProductService $productService)
    {
    }

    #[Route('/api/products', name: 'api_product_list', methods: ['GET'])]
    public function index(ProductRepository $productRepository): JsonResponse
    {
        return $this->productService->getAllProducts();
    }

    #[Route('/api/products', name: 'api_product_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        return $this->productService->createProduct($request);
    }

    #[Route('/api/products/{id}', name: 'api_product_show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        return $this->productService->getProduct($id);
    }

    #[Route('/api/products/{id}', name: 'api_products_update', methods: ['PUT'])]
    public function update(Product $product, Request $request): JsonResponse
    {
        return $this->productService->updateProduct($product, $request);
    }

    #[Route('/api/products/{id}', name: 'api_products_delete', methods: ['DELETE'])]
    public function destroy(int $id): JsonResponse
    {
        return $this->productService->deleteProduct($id);
    }
}
