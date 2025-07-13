<?php

namespace App\Application\Product\Service;


use App\Application\Menu\Service\MenuService;
use App\Application\Product\Dto\PriceRangeDto;
use App\Application\Product\Dto\ProductCategoryCollectionDto;
use App\Application\Product\Dto\ProductTypeDto;
use App\Domain\Product\Entity\ProductType;
use App\Domain\Product\Repository\TypeProductRepositoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

class ProductTypeService
{
    public function __construct(
        private readonly MenuService                    $menuService,
        private readonly TypeProductRepositoryInterface $typeProductRepository,
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
            return $dto;
        }, $entities);

        return  new ProductCategoryCollectionDto($productTypeDto);
    }
}
