<?php

declare(strict_types=1);

namespace App\Entity;

use Symfony\Component\Uid\Uuid;

class Product implements ItemInterface
{
    private ?string $id;
    private string $name;
    private float $quantity;
    private string $category;

    public function __construct(string $name, float $quantity, string $category)
    {
        $this->name = $name;
        $this->quantity = $quantity;
        $this->category = $category;
        $this->id = Uuid::v4()->toRfc4122();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getQuantity(): float
    {
        return $this->quantity;
    }

    public function getCategory(): string
    {
        return $this->category;
    }
}
