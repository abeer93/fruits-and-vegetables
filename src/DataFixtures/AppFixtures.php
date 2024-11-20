<?php

namespace App\DataFixtures;

use App\Entity\Fruit;
use App\Entity\Vegetable;
use App\Factory\ItemTypeStrategyFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    private $strategyFactory;

    public function __construct(ItemTypeStrategyFactory $strategyFactory)
    {
        $this->strategyFactory = $strategyFactory;
    }

    public function load(ObjectManager $manager): void
    {        
        $jsonData = json_decode(file_get_contents(__DIR__ . '/../../request.json'), true);

        foreach ($jsonData as $item) {
            try {
                $strategy = $this->strategyFactory->createStrategy($item['type']);
            } catch (\Throwable $th) {
                continue;
            }

            $strategy->createItem($item, $manager);
        }

        $manager->flush();
    }
}
