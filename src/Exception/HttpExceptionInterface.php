<?php

declare(strict_types=1);

namespace App\Exception;

interface HttpExceptionInterface
{
    public function getStatusCode(): int;
}
