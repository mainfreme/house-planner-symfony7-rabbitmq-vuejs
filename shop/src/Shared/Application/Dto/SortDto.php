<?php

declare(strict_types=1);

namespace App\Shared\Application\Dto;

class SortDto
{
    public function __construct(
        public string $sort = 'id',
        public string $order = 'ASC',
    ) {

    }

    public static function fromArray(array $array): self
    {
        return new self(
            sort: $array['sort'],
            order: $array['order'],
        );
    }

    public function getArray(): array
    {
        return get_object_vars($this);
    }
}
