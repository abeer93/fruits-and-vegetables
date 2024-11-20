<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class FruitRequest
{
    #[Assert\NotBlank(message: "The name is required and must not be empty.")]
    #[Assert\Type("string")]
    private string $name;

    #[Assert\NotBlank(message: "The quantity is required and must not be empty.")]
    #[Assert\Type("integer")]
    #[Assert\GreaterThan(0, message: "The quantity must be greater than 0.")]
    private int $quantity;

    #[Assert\NotBlank(message: "The unit is requiredand must not be empty.")]
    #[Assert\Choice(choices: ["g", "kg"], message: "The unit must be 'g' or 'kg'.")]
    private string $unit;

    public function __construct(string $name, int $quantity, string $unit)
    {
        $this->name = $name;
        $this->quantity = $quantity;
        $this->unit = $unit;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getUnit(): string
    {
        return $this->unit;
    }
}
