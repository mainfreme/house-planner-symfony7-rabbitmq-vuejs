<?php

declare(strict_types=1);

namespace App\Client\Application\Service;

use App\Client\Application\Dto\ClientDto;
use App\Client\Application\Dto\ClientFilterDto;
use App\Shared\Application\Dto\ColumnCollectionDto;
use App\Client\Domain\Entity\Client;
use App\Client\Domain\Repository\ClientRepositoryInterface;
use App\Infrastructure\Helper\CacheHelper;
use App\Shared\Application\Dto\PaginatedResultDto;
use App\Shared\Application\Dto\SortDto;
use App\Shared\Application\Service\FieldService;
use App\Shared\Application\ValueObject\Sort;

class ClientService
{
    private const CACHE_KEY = 'client';
    private const TTL = 3600;

    public function __construct(
        private readonly ClientRepositoryInterface $clientRepository,
        private readonly CacheHelper               $cacheHelper,
        private readonly FieldService              $fieldService
    )
    {
    }


    /**
     * @throws \Exception
     */
    public function getClient(int $id): ClientDto
    {
        $cacheKey = self::CACHE_KEY . '_' . $id;
        try {
            $clientCacheObject = $this->cacheHelper->getCache(ClientDto::class, $cacheKey);
            if (!$clientCacheObject) {
                $clientCacheObject = $this->findClient($id);
                $this->setClientCache($clientCacheObject, $cacheKey);
            }

            return $clientCacheObject;
        } catch (\Exception $e) {
            return $this->findClient($id);
        }
    }

    /**
     * @param int $clientId
     * @return ClientDto
     * @throws \Exception
     */
    public function findClient(int $clientId): ClientDto
    {
        $client = $this->clientRepository->findById($clientId);

        if (NULL === $client) {
            throw new \Exception('Nie odnaleziono klienta');
        }

        return $client;
    }


    /**
     * @throws \Exception
     */
    private function setClientCache($clientCacheObject, $cacheKey): void
    {
        try {
            $this->cacheHelper->forget($cacheKey);

            $this->cacheHelper
                ->setTtl(self::TTL)
                ->setCache($clientCacheObject, $cacheKey);
        } catch (\RedisException $e) {
            throw new \Exception($e->getMessage());
        }
    }


    /**
     * @param ClientDto $clientDto
     * @return ClientDto|null
     * @throws \Exception
     */
    public function update(ClientDto $clientDto): ?ClientDto
    {
        if (NULL === $clientDto->id) {
            throw new \Exception('Klient Id jest wymagany podczas aktualizacji');
        }

        $cacheKey = self::CACHE_KEY . '_' . $clientDto->id;

        $clientEntity = $this->clientRepository->update($clientDto);

        $client = ClientDto::fromEntity($clientEntity);

        $this->setClientCache($client, $cacheKey);

        return $client;
    }

    public function getFields(): ColumnCollectionDto
    {
        return $this->fieldService
            ->setCacheKey(self::CACHE_KEY)
            ->setEntity(Client::class)
            ->getFields();
    }

    public function findByCriteria(ClientFilterDto $criteria, SortDto $sortDto): PaginatedResultDto
    {
        $sort = new Sort(
            $sortDto->sort,
            $sortDto->order,
            $this->getFields(),
        );

        return $this->getClientByCriteria($criteria, $sort);
    }

    public function getClientByCriteria(ClientFilterDto $criteria, Sort $sort): PaginatedResultDto
    {
        $cacheKey = $this->cacheHelper->generateKey(self::CACHE_KEY, $criteria, $sort);

        try {
            $clientCacheObject = $this->cacheHelper->getCache(PaginatedResultDto::class, $cacheKey);
            if ($clientCacheObject === false) {
                $clientCacheObject = $this->findClientByCriteria($criteria, $sort);
                $this->cacheHelper
                    ->setTtl(self::TTL)
                    ->setCache($clientCacheObject, $cacheKey);
            }

            return $clientCacheObject;
        } catch (\Psr\Cache\CacheException|\RedisException $e) {
            return $this->findClientByCriteria($criteria, $sort);
        }
    }

    private function findClientByCriteria(ClientFilterDto $criteria, Sort $sort): PaginatedResultDto
    {
        return $this->clientRepository->findByCriteria($criteria, $sort);
    }

}
