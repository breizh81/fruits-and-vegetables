<?php

declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;

class StorageTypeException extends \Exception implements HttpExceptionInterface
{
    public function __construct(string $storageType, protected int $statusCode = Response::HTTP_BAD_REQUEST)
    {
        parent::__construct(\sprintf('Storage type "%s" is not supported.', $storageType));
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
