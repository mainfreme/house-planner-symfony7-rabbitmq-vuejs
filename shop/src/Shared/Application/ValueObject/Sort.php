<?php

declare(strict_types=1);

namespace App\Shared\Application\ValueObject;

use App\Shared\Application\Dto\ColumnCollectionDto;

class Sort
{
    const DIRECTION = ['ASC', 'DESC'];

    public function __construct(
        private ?string $field,
        private ?string $direction,
        ColumnCollectionDto $allowedColumnCollectionDto
    )
    {
        $this->field = in_array($field, $allowedColumnCollectionDto->toArray(), true) ? $field : 'id';
        $this->direction = in_array(strtoupper($direction), self::DIRECTION, true) ? strtoupper($direction) : 'ASC';
    }

    public function field(): string
    {
        return $this->field;
    }

    public function direction(): string
    {
        return $this->direction;
    }
}
