<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use App\Entity\User;

class ProductControllerTest extends WebTestCase
{
    private $client;
    private $token;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->token = $this->generateToken();
    }

    private function generateToken(): string
    {
        $user = $this->getUser();
        $tokenManager = static::getContainer()->get(JWTTokenManagerInterface::class);
        return $tokenManager->create($user);
    }

    private function getUser()
    {
        // Replace this with fetching a test user from your database or create a mock user
        // For simplicity, assuming you have a user with ID = 1
        return static::getContainer()->get('doctrine')->getRepository(User::class)->find(1);
    }

    private function createAuthenticatedClient()
    {
        $this->client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $this->token));
    }

    public function testGetProducts()
    {
        $this->createAuthenticatedClient();
        $this->client->request('GET', '/api/products');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testCreateProduct()
    {
        $this->createAuthenticatedClient();
        $data = ['name' => 'Test Product', 'price' => 99.99, 'sku' => 'SK-123'];

        $this->client->request(
            'POST',
            '/api/products',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($data)
        );

        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testGetSingleProduct()
    {
        $this->createAuthenticatedClient();
        $this->client->request('GET', '/api/products/1');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testUpdateProduct()
    {
        $this->createAuthenticatedClient();
        $data = ['name' => 'Updated Product', 'price' => 149.99, 'sku' => 'SK-456'];

        $this->client->request(
            'PUT',
            '/api/products/1',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($data)
        );

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testDeleteProduct()
    {
        $this->createAuthenticatedClient();
        $this->client->request('DELETE', '/api/products/1');

        $this->assertEquals(204, $this->client->getResponse()->getStatusCode());
    }

    public function testCreateProductValidation()
    {
        $this->createAuthenticatedClient();
        $data = ['name' => ''];

        $this->client->request(
            'POST',
            '/api/products',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($data)
        );

        $this->assertEquals(422, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testUpdateProductValidation()
    {
        $this->createAuthenticatedClient();
        $data = ['name' => ''];

        $this->client->request(
            'PUT',
            '/api/products/1',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($data)
        );

        $this->assertEquals(422, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }
}
