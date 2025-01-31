<?php

declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;

class InvalidJsonFormatException extends \Exception implements HttpExceptionInterface
{
    private const MESSAGE = 'Invalid JSON format';

    public function __construct(string $message = self::MESSAGE, int $statusCode = Response::HTTP_BAD_REQUEST)
    {
        parent::__construct($message);
        $this->statusCode = $statusCode;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
