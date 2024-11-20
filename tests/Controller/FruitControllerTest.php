<?php

namespace App\tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FruitControllerTest extends WebTestCase
{
    private $client;
    private $entityManager;

    protected function setUp(): void
    {
        $this->client = static::createClient();     
        $this->entityManager = static::getContainer()->get('doctrine')->getManager();
        $this->entityManager->beginTransaction();
    }

    protected function tearDown(): void
    {
        if ($this->entityManager->getConnection()->isTransactionActive()) {
            $this->entityManager->rollback();
        }

        $this->entityManager->close();
        parent::tearDown();
    }

    public function testGetFruitsWithoutFilters(): void
    {
        $this->seedDatabaseWithFruits('apple', 10);

        $this->client->request('GET', '/api/fruits');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);
        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertIsArray($responseData);
    }

    public function testGetFruitsWithInvalidFilter(): void
    {
        $this->client->request('GET', '/api/fruits', ['minQuantity' => -5]);

        $this->assertResponseStatusCodeSame(400);
        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('errors', $responseData);
    }

    public function testGetFruitsWithMultipleValidFilters(): void
    {
        $this->seedDatabaseWithFruits('apple', 15);
        $this->seedDatabaseWithFruits('banana', 5);
        $this->seedDatabaseWithFruits('watermelon', 10);

        $this->client->request('GET', '/api/fruits', [
            'minQuantity' => 5,
            'maxQuantity' => 10,
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);
        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertIsArray($responseData);
        $this->assertCount(2, $responseData);
        $this->assertNotEmpty($responseData);
    }

    public function testAddFruitHappyPath(): void
    {
        $this->client->request('POST', '/api/fruits', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'name' => 'Banana',
            'quantity' => 50,
            'unit' => 'kg',
        ]));

        $this->assertResponseStatusCodeSame(201);
        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('name', $responseData);
        $this->assertSame('Banana', $responseData['name']);
    }

    public function testAddFruitWithInvalidData(): void
    {
        $this->client->request('POST', '/api/fruits', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'name' => '',
            'quantity' => -10,
            'unit' => '',
        ]));

        $this->assertResponseStatusCodeSame(400);
        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('errors', $responseData);
    }
    
    public function testDeleteFruitHappyPath(): void
    {
        $createdFruit = $this->seedDatabaseWithFruits('apple', 15);

        $this->client->request('DELETE', '/api/fruits/' . $createdFruit->getId());

        $this->assertResponseStatusCodeSame(204);
        $this->assertEmpty($this->client->getResponse()->getContent());
    }

    private function seedDatabaseWithFruits($name, $quantity)
    {
        $fruit = new \App\Entity\Fruit();
        $fruit->setName($name);
        $fruit->setQuantity($quantity);
        $this->entityManager->persist($fruit);
        $this->entityManager->flush();

        return $fruit;
    }
}
