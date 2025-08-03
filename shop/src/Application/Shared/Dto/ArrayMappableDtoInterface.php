<?php

declare(strict_types=1);

namespace App\Application\Shared\Dto;

interface ArrayMappableDtoInterface
{
    public static function fromArray(array $dto);
}
