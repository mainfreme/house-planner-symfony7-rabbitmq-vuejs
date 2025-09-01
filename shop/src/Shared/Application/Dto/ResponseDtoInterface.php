<?php

declare(strict_types=1);

namespace App\Shared\Application\Dto;

interface ResponseDtoInterface
{
    public function getArray(): array;

    public function toApiArray(): array;
}
