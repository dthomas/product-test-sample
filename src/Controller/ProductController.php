<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends BaseController
{
    #[Route('/api/products', name: 'api_product_list', methods: ['GET'])]
    public function index(ProductRepository $productRepository): JsonResponse
    {
        $products = $productRepository->findAll();

        return $this->json($products);
    }

    #[Route('/api/products', name: 'api_product_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $product = new Product();
        $data = (array)json_decode($request->getContent(), true);
        $form = $this->createForm(ProductType::class, $product);
        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setCreatedAt(new \DateTimeImmutable());

            $em->persist($product);
            $em->flush();

            return $this->json([
                'success' => true,
                'id' => $product->getId(),
            ], JsonResponse::HTTP_CREATED);
        }

        return $this->json([
            'success' => false,
            'errors' => $this->getErrorsFromForm($form),
        ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
    }

    #[Route('/api/products/{id}', name: 'api_product_show', methods: ['GET'])]
    public function show(int $id, ProductRepository $productRepository): JsonResponse
    {
        $product = $productRepository->find($id);

        if (!$product) {
            return new JsonResponse(
                ['error' => 'Product not found'],
                JsonResponse::HTTP_NOT_FOUND
            );
        }

        return $this->json($product);
    }

    #[Route('/api/products/{id}', name: 'api_products_update', methods: ['PUT'])]
    public function update(Product $product, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = (array)json_decode($request->getContent(), true);
        $form = $this->createForm(ProductType::class, $product);
        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setUpdatedAt(new \DateTimeImmutable());

            $em->persist($product);
            $em->flush();

            return $this->json([
                'success' => true,
                'id' => $product->getId(),
            ], JsonResponse::HTTP_OK);
        }

        return $this->json([
            'success' => false,
            'errors' => $this->getErrorsFromForm($form),
        ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
    }

    #[Route('/api/products/{id}', name: 'api_products_delete', methods: ['DELETE'])]
    public function destroy(Product $product, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($product);
        $em->flush();
        return $this->json([], JsonResponse::HTTP_NO_CONTENT);
    }
}
