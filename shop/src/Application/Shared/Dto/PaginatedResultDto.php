<?php

namespace App\Application\Shared\Dto;

class PaginatedResultDto implements ResponseDtoInterface
{
    public function __construct(
        public readonly int   $total,
        public readonly int   $page,
        public readonly int   $limit,
        public readonly int   $pages,
        public readonly array $items,
    )
    {
    }

    /**
     * @return array<int|array<>>
     */
    public function getArray(): array
    {
        return get_object_vars($this);
    }
}
