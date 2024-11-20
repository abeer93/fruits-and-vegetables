<?php

namespace App\Strategy;

use App\Entity\Vegetable;
use Doctrine\Persistence\ObjectManager;
use App\Utils\QuantityConverter;

class VegetableStrategy implements ItemTypeStrategy
{
    public function createItem(array $itemData, ObjectManager $manager): void
    {
        $vegetable = new Vegetable();
        $vegetable->setName($itemData['name']);
        $quantity = QuantityConverter::convertToGrams($itemData['quantity'], $itemData['unit']);
        $vegetable->setQuantity($quantity);
        $manager->persist($vegetable);
    }
}
