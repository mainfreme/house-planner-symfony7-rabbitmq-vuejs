<?php

declare(strict_types=1);

namespace App\Shared\Application\Dto;

final class PaginatedResultDto implements ResponseDtoInterface
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

    public function toApiArray(): array
    {
        return [
            'data' => $this->items,
            'meta' => [
                'total_item' => $this->total,
                'per_page' => $this->limit,
                'total_page' => $this->page,
                'last_page' => $this->pages,
            ],
            'links' => [
                'first' => '?page=1',
                'last' => '?page=' . $this->pages,
                'prev' => $this->page > 1 ? '?page=' . ($this->page - 1) : null,
                'next' => $this->page < $this->pages ? '?page=' . ($this->page + 1) : null,
            ]
        ];
    }

    /**
     * @return array<int|array<>>
     */
    public function getArray(): array
    {
        return get_object_vars($this);
    }
}
