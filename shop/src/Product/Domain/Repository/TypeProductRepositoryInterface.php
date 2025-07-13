<?php

declare(strict_types=1);

namespace App\Product\Domain\Repository;

use App\Product\Domain\Entity\ProductType;

interface TypeProductRepositoryInterface
{
    public function save(ProductType $typeProduct): void;

    public function findById(int $id): ?ProductType;

    public function findByName(string $name): ?ProductType;

    public function findAll(): array;

    public function findActive(): array;

    public function remove(ProductType $typeProduct): void;
}
