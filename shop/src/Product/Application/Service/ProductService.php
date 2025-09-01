<?php

declare(strict_types=1);

namespace App\Product\Application\Service;

use App\Infrastructure\Helper\CacheHelper;
use App\Product\Application\Dto\ProductFilterDto;
use App\Product\Domain\Entity\Product;
use App\Product\Domain\Repository\ProductRepositoryInterface;
use App\Shared\Application\Dto\ColumnArray;
use App\Shared\Application\Dto\ColumnCollectionDto;
use App\Shared\Application\Dto\PaginatedResultDto;
use App\Shared\Application\Dto\ResponseDtoInterface;
use App\Shared\Application\Dto\SortDto;
use App\Shared\Application\Service\TransformService;
use App\Shared\Application\ValueObject\Sort;

class ProductService
{
    private const CACHE_KEY = 'products';
    private const TTL = 3600 * 24;

    public function __construct(
        private readonly ProductRepositoryInterface $productRepository,
        private readonly CacheHelper                $cacheHelper,
        private readonly TransformService           $transformService,
    )
    {
    }

    public function getProductList(ProductFilterDto $filterDto, SortDto $sortDto)
    {
        $cacheKey = $this->cacheHelper->generateKey(self::CACHE_KEY . '_list', $filterDto);

        try {
            $clientCacheObject = $this->cacheHelper->getCache(PaginatedResultDto::class, $cacheKey);
            if ($clientCacheObject === false) {
                $clientCacheObject = $this->findProductList($filterDto, $sortDto);
                $this->cacheHelper
                    ->setTtl(self::TTL)
                    ->setCache($clientCacheObject, $cacheKey);
            }

            return $clientCacheObject;
        } catch (\Psr\Cache\CacheException|\RedisException $e) {
            return $this->findProductList($filterDto, $sortDto);
        }
    }

    private function findProductList(ProductFilterDto $filterDto, SortDto $sortDto): ResponseDtoInterface
    {
        $sort = new Sort(
            $sortDto->sort,
            $sortDto->order,
            $this->getFields()
        );

        return $this->productRepository->findByCriteria($filterDto, $sort);
    }

    private function getFields()
    {
        $cacheKey = self::CACHE_KEY . '_fields_allowed';
        try {
            $columnCollectionCache = $this->cacheHelper->getCache(ColumnCollectionDto::class, $cacheKey);
            if (!$columnCollectionCache) {
                $columnCollectionCache = new ColumnCollectionDto($this->getColumns());
                $this->cacheHelper
                    ->setTtl(self::TTL)
                    ->setCache($columnCollectionCache, $cacheKey);
            }

            return $columnCollectionCache;

        } catch (\Psr\Cache\CacheException|\RedisException $e) {
            dd($e->getMessage());
            return new ColumnCollectionDto($this->getColumns());
        }
    }

    private function getColumns(): array
    {
        try {
            return $this->transformService->getColumnsName(Product::class)
                ->transformColumns()
                ->getColumnsTransformDto(ColumnArray::class);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }


}
