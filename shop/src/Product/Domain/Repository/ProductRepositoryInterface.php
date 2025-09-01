<?php

declare(strict_types=1);

namespace App\Product\Domain\Repository;

use App\Product\Application\Dto\ProductFilterDto;
use App\Product\Domain\Entity\Product;
use App\Product\Domain\Entity\ProductType;
use App\Shared\Application\Dto\PaginatedResultDto;
use App\Shared\Application\ValueObject\Sort;

interface ProductRepositoryInterface
{
    public function save(Product $product): void;

    public function findById(int $id): ?Product;

    public function findByType(ProductType $typeProduct): array;

    public function findByName(string $name): ?Product;

    public function findAll(): array;

    public function remove(Product $product): void;

    public function findMinMaxPrice(string $category, bool $active): array;

    public function findByCriteria(ProductFilterDto $criteria, Sort $sort): PaginatedResultDto;
}
