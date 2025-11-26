<?php

declare(strict_types=1);

namespace App\Client\Application\Exceptions;

use PHPUnit\Event\Code\Throwable;

class ClientException extends \Exception
{
    public function __construct(string $message = "Wystąpił błąd Klienta", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
