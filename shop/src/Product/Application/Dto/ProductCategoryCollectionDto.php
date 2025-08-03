<?php

declare(strict_types=1);

namespace App\Product\Application\Dto;

use App\Application\Shared\Dto\ArrayMappableDtoInterface;
use App\Application\Shared\Dto\ResponseDtoInterface;

class ProductCategoryCollectionDto implements ResponseDtoInterface
{
    /**
     * @var ProductTypeDto[]
     */
    private array $items = [];

    /**
     * @param ProductTypeDto[] $items
     */
    public function __construct(array $items = [])
    {
        foreach ($items as $item) {
            $this->addItem($item);
        }
    }

    /**
     * @return ProductTypeDto[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function addItem(ProductTypeDto $item): void
    {
        $this->items[] = $item;
    }

    public function isEmpty(): bool
    {
        return empty($this->items);
    }

    /**
     * @return array<array<string>>
     */
    public function getArray(): array
    {
        return array_map(fn(ProductTypeDto $dto) => $dto->getArray(), $this->items);
    }
}
