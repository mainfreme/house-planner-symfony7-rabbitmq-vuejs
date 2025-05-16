<?php

namespace App\Domain\Product\Repository;

use App\Domain\Product\Entity\Product;
use App\Domain\Product\Entity\ProductType;

interface ProductRepositoryInterface
{
    public function save(Product $product): void;

    public function findById(int $id): ?Product;

    public function findByType(ProductType $typeProduct): array;

    public function findByName(string $name): ?Product;

    public function findAll(): array;

    public function remove(Product $product): void;

    public function findByCriteria(array $criteria): array;
}
