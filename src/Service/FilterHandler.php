<?php

namespace App\Service;

use Doctrine\ORM\QueryBuilder;

class FilterHandler
{
    public function applyFilters(QueryBuilder $queryBuilder, array $filters, string $alias = 'f'): QueryBuilder
    {
        if (!empty($filters['name'])) {
            $queryBuilder->andWhere("lower($alias.name) LIKE :name")
                         ->setParameter('name', '%' . strtolower($filters['name']) . '%');
        }

        if (!empty($filters['minQuantity'])) {
            $queryBuilder->andWhere("$alias.quantity >= :minQuantity")
                         ->setParameter('minQuantity', $filters['minQuantity']);
        }

        if (!empty($filters['maxQuantity'])) {
            $queryBuilder->andWhere("$alias.quantity <= :maxQuantity")
                         ->setParameter('maxQuantity', $filters['maxQuantity']);
        }

        $sortField = $filters['sort'] ?? 'id';
        $sortOrder = strtolower($filters['order'] ?? 'asc');
        $queryBuilder->orderBy("$alias.$sortField", $sortOrder);

        if (!empty($filters['page']) && !empty($filters['limit'])) {
            $offset = ($filters['page'] - 1) * $filters['limit'];
            $queryBuilder->setFirstResult($offset)
                         ->setMaxResults($filters['limit']);
        }

        return $queryBuilder;
    }
}
