<?php

declare(strict_types=1);

namespace App\Client\Application\Exceptions;

class DataBaseException extends \Exception
{
    public function __construct(string $message = "", int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
