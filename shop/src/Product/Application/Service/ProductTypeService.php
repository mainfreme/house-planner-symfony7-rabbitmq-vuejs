<?php

declare(strict_types=1);

namespace App\Product\Application\Service;

use App\Menu\Application\Service\MenuService;
use App\Product\Application\Dto\PriceRangeDto;
use App\Product\Application\Dto\ProductCategoryCollectionDto;
use App\Product\Application\Dto\ProductTypeDto;
use App\Product\Domain\Entity\ProductType;
use App\Product\Domain\Repository\ProductTypeRepositoryInterface;

#[AsService]
class ProductTypeService
{
    public function __construct(
        private readonly MenuService                    $menuService,
        private readonly ProductTypeRepositoryInterface $typeProductRepository,
    )
    {
    }


    public function add(ProductType $productType): bool
    {
        try {
            $this->typeProductRepository->save($productType);
        } catch (\Exception $e) {
            // logowanie błędu do pliku
            return false;
        }

        $this->menuService->refreshCache();

        return true;
    }

    public function getList(): ProductCategoryCollectionDto
    {
        $entities = $this->typeProductRepository->findActive();

        $productTypeDto = array_map(function (ProductType $productType): ProductTypeDto {
            $dto = new ProductTypeDto();
            $dto->setId($productType->getId());
            $dto->setName($productType->getName());
            $dto->setLink($productType->getLink());
            return $dto;
        }, $entities);

        return new ProductCategoryCollectionDto($productTypeDto);
    }
}
