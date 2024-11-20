<?php

namespace App\Service;

use App\Entity\Vegetable;
use App\Utils\QuantityConverter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class VegetableService
{
    private EntityManagerInterface $em;
    private $filterHandler;

    public function __construct(EntityManagerInterface $em, FilterHandler $filterHandler)
    {
        $this->em = $em;
        $this->filterHandler = $filterHandler;
    }

    public function addVegetable(string $name, int $quantity, string $unit): Vegetable
    {
        $vegetable = new Vegetable();
        $vegetable->setName($name);
        $quantity = QuantityConverter::convertToGrams($quantity, $unit);
        $vegetable->setQuantity($quantity);
        $this->em->persist($vegetable);
        $this->em->flush();
        return $vegetable;
    }

    public function removeVegetable(int $id): void
    {
        $vegetable = $this->em->getRepository(Vegetable::class)->find($id);
        if (!$vegetable) {
            throw new NotFoundHttpException("Vegetable not found");
        }
        $this->em->remove($vegetable);
        $this->em->flush();
    }

    public function listVegetables(array $filters): array
    {
        $queryBuilder = $this->em->getRepository(Vegetable::class)->createQueryBuilder('f');

        $queryBuilder = $this->filterHandler->applyFilters($queryBuilder, $filters);

        return $queryBuilder->getQuery()->getResult();
    }
}
