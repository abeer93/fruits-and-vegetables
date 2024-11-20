<?php

namespace App\Factory;

use App\Strategy\FruitStrategy;
use App\Strategy\VegetableStrategy;
use App\Strategy\ItemTypeStrategy;

class ItemTypeStrategyFactory
{
    public function createStrategy(string $itemType): ItemTypeStrategy
    {
        switch ($itemType) {
            case 'fruit':
                return new FruitStrategy();
            case 'vegetable':
                return new VegetableStrategy();
            default:
                throw new \InvalidArgumentException("Unknown item type: $itemType");
        }
    }
}
