<?php

declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;

class InvalidCategoryException extends \Exception implements HttpExceptionInterface
{
    private const MESSAGE = 'Invalid category';

    public function __construct(string $message = self::MESSAGE, protected int $statusCode = Response::HTTP_BAD_REQUEST)
    {
        parent::__construct($message);
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
