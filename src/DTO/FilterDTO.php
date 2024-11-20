<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;

class FilterDTO
{
    #[Assert\Type('string')]
    #[Assert\NotBlank(allowNull: true)]
    private ?string $name;

    #[Assert\Type('integer')]
    #[Assert\PositiveOrZero]
    private ?int $minQuantity;

    #[Assert\Type('integer')]
    #[Assert\PositiveOrZero]
    private ?int $maxQuantity;

    #[Assert\Type('string')]
    #[Assert\Choice(choices: ['id', 'name', 'quantity'], message: 'Invalid sort field.')]
    private string $sort;

    #[Assert\Type('string')]
    #[Assert\Choice(choices: ['asc', 'desc'], message: 'Invalid sort order.')]
    private string $order;

    #[Assert\Type('integer')]
    #[Assert\Positive]
    private int $page;

    #[Assert\Type('integer')]
    #[Assert\Positive]
    private int $limit;

    public function __construct(array $queryParams)
    {
        $this->name = $queryParams['name'] ?? null;
        $this->minQuantity = isset($queryParams['minQuantity']) ? (int) $queryParams['minQuantity'] : null;
        $this->maxQuantity = isset($queryParams['maxQuantity']) ? (int) $queryParams['maxQuantity'] : null;
        $this->sort = $queryParams['sort'] ?? 'id';
        $this->order = $queryParams['order'] ?? 'asc';
        $this->page = isset($queryParams['page']) ? (int) $queryParams['page'] : 1;
        $this->limit = isset($queryParams['limit']) ? (int) $queryParams['limit'] : 10;
    }

    public function validate(): array
    {
        $validator = Validation::createValidator();
        $violations = $validator->validate($this);

        $errors = [];
        foreach ($violations as $violation) {
            $errors[] = $violation->getMessage();
        }

        return $errors;
    }

    public function toFilters(): array
    {
        $filters = [];

        if (!empty($this->name)) {
            $filters['name'] = $this->name;
        }

        if ($this->minQuantity !== null) {
            $filters['minQuantity'] = $this->minQuantity;
        }

        if ($this->maxQuantity !== null) {
            $filters['maxQuantity'] = $this->maxQuantity;
        }

        $filters['sort'] = $this->sort;
        $filters['order'] = $this->order;
        $filters['page'] = $this->page;
        $filters['limit'] = $this->limit;

        return $filters;
    }
}
