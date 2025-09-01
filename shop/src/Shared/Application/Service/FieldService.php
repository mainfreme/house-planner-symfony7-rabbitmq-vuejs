<?php

declare(strict_types=1);

namespace App\Shared\Application\Service;

use App\Client\Domain\Repository\ClientRepositoryInterface;
use App\Infrastructure\Helper\CacheHelper;
use App\Shared\Application\Dto\ColumnArray;
use App\Shared\Application\Dto\ColumnCollectionDto;

class FieldService
{

    private string $entity;
    private string $cacheKey;
    private int $cacheTtl = 3600;

    public function __construct(
        private readonly CacheHelper      $cacheHelper,
        private readonly TransformService $transformService,
    )
    {
    }

    public function getFields(): ColumnCollectionDto
    {
        $cacheKey = $this->cacheKey . '_fields_allowed';

        try {
            $columnCollectionCache = $this->cacheHelper->getCache(ColumnCollectionDto::class, $cacheKey);
            if (!$columnCollectionCache) {
                $columnCollectionCache = new ColumnCollectionDto($this->getColumns());
                $this->cacheHelper
                    ->setTtl($this->cacheTtl)
                    ->setCache($columnCollectionCache, $cacheKey);

            }

            return $columnCollectionCache;

        } catch (\Psr\Cache\CacheException|\RedisException $e) {
            dd($e->getMessage());
            return new ColumnCollectionDto($this->getColumns());
        }
    }

    private function getColumns()
    {
        try {
            return $this->transformService->getColumnsName($this->entity)
                ->transformColumns()
                ->getColumnsTransformDto(ColumnArray::class);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    /**
     * @param string $entity
     * @return FieldService
     */
    public function setEntity(string $entity): FieldService
    {
        $this->entity = $entity;
        return $this;
    }

    /**
     * @param string $cacheKey
     * @return FieldService
     */
    public function setCacheKey(string $cacheKey): FieldService
    {
        $this->cacheKey = $cacheKey;
        return $this;
    }

    /**
     * @param int $cacheTtl
     * @return FieldService
     */
    public function setCacheTtl(int $cacheTtl): FieldService
    {
        $this->cacheTtl = $cacheTtl;
        return $this;
    }
}
