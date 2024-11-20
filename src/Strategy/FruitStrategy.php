<?php

namespace App\Strategy;

use App\Entity\Fruit;
use Doctrine\Persistence\ObjectManager;
use App\Utils\QuantityConverter;

class FruitStrategy implements ItemTypeStrategy
{
    public function createItem(array $itemData, ObjectManager $manager): void
    {
        $fruit = new Fruit();
        $fruit->setName($itemData['name']);
        $quantity = QuantityConverter::convertToGrams($itemData['quantity'], $itemData['unit']);
        $fruit->setQuantity($quantity);
        $manager->persist($fruit);
    }
}
