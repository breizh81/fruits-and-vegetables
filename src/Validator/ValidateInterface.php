<?php

declare(strict_types=1);

namespace App\Validator;

interface ValidateInterface
{
    public function validate(object $entity): array;
}
