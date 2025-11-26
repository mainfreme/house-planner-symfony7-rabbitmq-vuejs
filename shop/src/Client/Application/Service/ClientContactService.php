<?php

declare(strict_types=1);

namespace App\Client\Application\Service;

use App\Client\Application\Dto\ClientContactDto;
use App\Client\Application\Dto\ClientContactFilterDto;
use App\Client\Domain\Repository\ClientContactRepositoryInterface;
use App\Client\Domain\Repository\ClientRepositoryInterface;
use App\Infrastructure\Helper\CacheHelper;
use App\Shared\Application\Dto\PaginatedResultDto;
use App\Shared\Application\Service\TransformService;
use App\Shared\Application\ValueObject\Sort;

class ClientContactService
{
    private const CACHE_KEY = 'client_contact';
    private const TTL = 3600;

    public function __construct(
        private readonly ClientContactRepositoryInterface $clientRepository,
        private readonly CacheHelper                      $cacheHelper,
        private readonly TransformService                 $transformService
    )
    {
    }

    /**
     * @throws \Exception
     */
    public function getContact(int $id): ClientContactDto
    {
        $cacheKey = self::CACHE_KEY . '_' . $id;
        try {
            $clientCacheObject = $this->cacheHelper->getCache(ClientContactDto::class, $cacheKey);
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
    public function findClient(int $clientId): ClientContactDto
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
     * @param int $clientId
     * @param ClientContactDto $clientContactDto
     * @return ClientContactDto|null
     * @throws \Exception
     */
    public function addClientContact(int $clientId, ClientContactDto $clientContactDto): ?ClientContactDto
    {
        $client = $this->findClient($clientId);
        if (!$client) {
            throw new \RuntimeException('Client not found');
        }

        $clientContactDto->setClient($client);

        $clientContact = $this->clientRepository->save($clientContactDto);

        return ClientContactDto::fromEntity($clientContact);
    }


    /**
     * @param ClientContactFilterDto $filterDto
     * @param $sortDto
     * @return PaginatedResultDto
     */
    public function findByCriteria(ClientContactFilterDto $filterDto, $sortDto): PaginatedResultDto
    {
        $sort = new Sort(
            $sortDto->sort,
            $sortDto->order
        );

        return $this->getClientByCriteria($filterDto, $sort);
    }

    /**
     * @param ClientContactFilterDto $filterDto
     * @param Sort $sort
     * @return PaginatedResultDto
     */
    private function getClientByCriteria(ClientContactFilterDto $filterDto, Sort $sort): PaginatedResultDto
    {
        $cacheKey = $this->cacheHelper->generateKey(self::CACHE_KEY, $filterDto, $sort);

        try {
            $clientCacheObject = $this->cacheHelper->getCache(PaginatedResultDto::class, $cacheKey);
            if ($clientCacheObject === false) {
                $clientCacheObject = $this->findClientByCriteria($filterDto, $sort);
                $this->cacheHelper
                    ->setTtl(self::TTL)
                    ->setCache($clientCacheObject, $cacheKey);
            }

            return $clientCacheObject;
        } catch (\Psr\Cache\CacheException|\RedisException $e) {
            return $this->findClientByCriteria($filterDto, $sort);
        }
    }

    private function findClientByCriteria(ClientContactFilterDto $criteria, Sort $sort): PaginatedResultDto
    {
        return $this->clientRepository->findByCriteria($criteria, $sort);
    }


}
