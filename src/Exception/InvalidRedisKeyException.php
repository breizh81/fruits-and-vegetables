<?php

declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;

class InvalidRedisKeyException extends \Exception implements HttpExceptionInterface
{
    public function __construct(string $key, protected int $statusCode = Response::HTTP_BAD_REQUEST)
    {
        parent::__construct(\sprintf('No Redis key provider found for entity type: %s', $key));
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
