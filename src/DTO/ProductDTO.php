<?php

declare(strict_types=1);

namespace App\DTO;

use App\Enum\CategoryEnum;
use App\Enum\UnitEnum;
use Symfony\Component\Validator\Constraints as Assert;

class ProductDTO
{
    #[Assert\NotBlank]
    #[Assert\Type('string')]
    public string $name;

    #[Assert\NotBlank]
    #[Assert\Type('float')]
    #[Assert\Positive(message: 'Quantity must be greater than zero.')]
    public float $quantity;

    #[Assert\NotBlank]
    #[Assert\Choice(choices: [UnitEnum::GRAMS->value, UnitEnum::KILOGRAMS->value], message: "Choose a valid unit: 'kg' or 'g'.")]
    public string $unit;

    #[Assert\NotBlank]
    #[Assert\Choice(choices: [CategoryEnum::FRUIT->value, CategoryEnum::VEGETABLE->value], message: 'Invalid category.')]
    public string $category;

    public function __construct(string $name, float $quantity, string $unit, string $category)
    {
        $this->name = $name;
        $this->quantity = $quantity;
        $this->unit = $unit;
        $this->category = $category;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['name'],
            $data['quantity'],
            $data['unit'] ?? UnitEnum::GRAMS->value,
            $data['category']
        );
    }
}
