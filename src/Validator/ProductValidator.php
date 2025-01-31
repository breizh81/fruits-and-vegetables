<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Validator\ValidatorInterface;

final readonly class ProductValidator implements ValidateInterface
{
    public function __construct(private readonly ValidatorInterface $validator)
    {
    }

    public function validate(object $entity): array
    {
        $violations = $this->validator->validate($entity);

        if (0 === \count($violations)) {
            return [];
        }

        return array_map(fn ($violation) => $violation->getMessage(), iterator_to_array($violations));
    }
}
