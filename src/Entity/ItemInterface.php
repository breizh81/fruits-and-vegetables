<?php

declare(strict_types=1);

namespace App\Entity;

interface ItemInterface
{
    public function getId(): string;

    public function getName(): string;

    public function getQuantity(): float;

    public function getCategory(): string;
}
