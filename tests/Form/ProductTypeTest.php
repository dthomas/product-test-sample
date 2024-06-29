<?php

declare(strict_types=1);

namespace App\Tests\Form;

use App\Entity\Product;
use App\Form\ProductType;
use Symfony\Component\Form\Test\TypeTestCase;

class ProductTypeTest extends TypeTestCase
{
    public function testSubmitValidData(): void
    {
        $formData = [
            'name' => 'Test Product',
            'price' => 19.99,
            'sku' => 'ABC123',
        ];

        $model = new Product();
        // The initial data that the form will be initialized with
        $form = $this->factory->create(ProductType::class, $model);

        // Submit the data to the form directly
        $form->submit($formData);

        $expected = new Product();
        $expected->setName('Test Product');
        $expected->setPrice("19.99");
        $expected->setSku('ABC123');

        // Assert the form is synchronized:
        $this->assertTrue($form->isSynchronized());

        // Check that $model was modified as expected when the form is submitted
        $this->assertEquals($expected, $model);

        // Check that the form elements have been created correctly
        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }

    public function testConfigureOptions(): void
    {
        $formType = new ProductType();
        $resolver = new \Symfony\Component\OptionsResolver\OptionsResolver();
        $formType->configureOptions($resolver);

        $options = $resolver->resolve();

        $this->assertArrayHasKey('data_class', $options);
        $this->assertEquals(Product::class, $options['data_class']);
        $this->assertArrayHasKey('csrf_protection', $options);
        $this->assertFalse($options['csrf_protection']);
    }
}
