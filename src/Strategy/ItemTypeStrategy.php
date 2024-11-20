<?php

namespace App\Strategy;

use Doctrine\Persistence\ObjectManager;

interface ItemTypeStrategy
{
    public function createItem(array $itemData, ObjectManager $manager): void;
}
