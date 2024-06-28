<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final class ProductService
{
    use CanGetErrorsFromFormTrait;

    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly ProductRepository $productRepository,
        private readonly FormFactoryInterface $formFactory,
    ) {
    }

    public function getAllProducts(): JsonResponse
    {
        return new JsonResponse($this->productRepository->findAll());
    }

    public function createProduct(Request $request): JsonResponse
    {
        $product = new Product();
        $data = (array)json_decode($request->getContent(), true);
        $form = $this->formFactory->create(ProductType::class, $product);
        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setCreatedAt(new \DateTimeImmutable());

            $this->em->persist($product);
            $this->em->flush();

            return new JsonResponse([
                'success' => true,
                'id' => $product->getId(),
            ], JsonResponse::HTTP_CREATED);
        }

        return new JsonResponse([
            'success' => false,
            'errors' => $this->getErrorsFromForm($form),
        ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function getProduct(int $id): JsonResponse
    {
        $product = $this->productRepository->find($id);

        if (!$product) {
            return new JsonResponse(
                ['error' => 'Product not found'],
                JsonResponse::HTTP_NOT_FOUND
            );
        }

        return new JsonResponse($product);
    }

    public function updateProduct(Product $product, Request $request): JsonResponse
    {
        $data = (array)json_decode($request->getContent(), true);
        $form = $this->formFactory->create(ProductType::class, $product);
        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setUpdatedAt(new \DateTimeImmutable());

            $this->em->persist($product);
            $this->em->flush();

            return new JsonResponse([
                'success' => true,
                'id' => $product->getId(),
            ], JsonResponse::HTTP_OK);
        }

        return new JsonResponse([
            'success' => false,
            'errors' => $this->getErrorsFromForm($form),
        ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function deleteProduct(int $id): JsonResponse
    {
        $product = $this->productRepository->find($id);

        if (!$product) {
            return new JsonResponse(
                ['error' => 'Product not found'],
                JsonResponse::HTTP_NOT_FOUND
            );
        }

        $this->em->remove($product);
        $this->em->flush();
        return new JsonResponse([], JsonResponse::HTTP_NO_CONTENT);
    }
}
