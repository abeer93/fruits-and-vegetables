<?php

namespace App\Service;

use App\Entity\Fruit;
use App\Utils\QuantityConverter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FruitService
{
    private EntityManagerInterface $em;
    private $filterHandler;

    public function __construct(EntityManagerInterface $em, FilterHandler $filterHandler)
    {
        $this->em = $em;
        $this->filterHandler = $filterHandler;
    }

    public function addFruit(string $name, int $quantity, string $unit): Fruit
    {
        $fruit = new Fruit();
        $fruit->setName($name);
        $quantity = QuantityConverter::convertToGrams($quantity, $unit);
        $fruit->setQuantity($quantity);
        $this->em->persist($fruit);
        $this->em->flush();
        return $fruit;
    }

    public function removeFruit(int $id): void
    {
        $fruit = $this->em->getRepository(Fruit::class)->find($id);
        if (!$fruit) {
            throw new NotFoundHttpException("Fruit not found");
        }
        $this->em->remove($fruit);
        $this->em->flush();
    }

    public function listFruits(array $filters): array
    {
        $queryBuilder = $this->em->getRepository(Fruit::class)->createQueryBuilder('f');

        $queryBuilder = $this->filterHandler->applyFilters($queryBuilder, $filters);

        return $queryBuilder->getQuery()->getResult();
    }
}
