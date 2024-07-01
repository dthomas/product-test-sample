<?php

namespace App\Tests\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\Product;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;

class ProductTest extends TestCase
{
    private $validator;

    protected function setUp(): void
    {
        $this->validator = Validation::createValidatorBuilder()
            ->enableAttributeMapping()
            ->getValidator();
    }

    public function testGetAndSetName()
    {
        $product = new Product();
        $productName = "Test Product";

        $product->setName($productName);

        $this->assertSame($productName, $product->getName());
    }

    public function testGetAndSetPrice()
    {
        $product = new Product();
        $productPrice = "99.99";

        $product->setPrice($productPrice);

        $this->assertSame($productPrice, $product->getPrice());
    }

    public function testGetAndSetSku()
    {
        $product = new Product();
        $productSku = "SKU12345";

        $product->setSku($productSku);

        $this->assertSame($productSku, $product->getSku());
    }

    public function testGetAndSetCreatedAt()
    {
        $product = new Product();
        $createdAt = new \DateTimeImmutable();

        $product->setCreatedAt($createdAt);

        $this->assertSame($createdAt, $product->getCreatedAt());
    }

    public function testGetAndSetUpdatedAt()
    {
        $product = new Product();
        $updatedAt = new \DateTimeImmutable();

        $product->setUpdatedAt($updatedAt);

        $this->assertSame($updatedAt, $product->getUpdatedAt());
    }

    public function testInvalidProductName()
    {
        $product = new Product();
        $product->setName('');

        $violations = $this->validator->validate($product);

        $this->assertGreaterThan(0, count($violations));
    }

    public function testInvalidProductPrice()
    {
        $product = new Product();
        $product->setPrice('-10.00');

        $violations = $this->validator->validate($product);

        $this->assertGreaterThan(0, count($violations));
    }

    public function testInvalidProductSku()
    {
        $product = new Product();
        $product->setSku('');

        $violations = $this->validator->validate($product);

        $this->assertGreaterThan(0, count($violations));
    }

    public function testValidProduct()
    {
        $product = new Product();
        $product->setName('Valid Product');
        $product->setPrice('100.00');
        $product->setSku('VALIDSKU');

        $violations = $this->validator->validate($product);

        $this->assertCount(0, $violations);
    }
}
