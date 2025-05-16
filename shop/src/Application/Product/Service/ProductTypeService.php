<?php

namespace App\Application\Product\Service;


use App\Application\Menu\Service\MenuService;
use App\Domain\Product\Entity\ProductType;
use App\Domain\Product\Repository\TypeProductRepositoryInterface;

class ProductTypeService
{
    public function __construct(
        private readonly MenuService $menuService,
        private readonly TypeProductRepositoryInterface $typeProductRepository
    ) {
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
}
