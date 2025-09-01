<?php

declare(strict_types=1);

namespace App\Shared\Application\Dto;

interface ArrayMappableInterface
{
    public static function fromArray(array $dto);
}
