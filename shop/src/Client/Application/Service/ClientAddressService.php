<?php

declare(strict_types=1);

namespace App\Client\Application\Service;

use App\Client\Application\Dto\ClientAddressDto;
use App\Client\Application\Dto\ClientAddressFilterDto;
use App\Client\Domain\Entity\ClientAddress;
use App\Client\Domain\Repository\ClientAddressRepositoryInterface;
use App\Client\Domain\Repository\ClientRepositoryInterface;
use App\Infrastructure\Helper\CacheHelper;
use App\Shared\Application\Dto\PaginatedResultDto;
use App\Shared\Application\Dto\SortDto;
use App\Shared\Application\Service\FieldService;
use App\Shared\Application\ValueObject\Sort;
use Doctrine\ORM\EntityNotFoundException;

class ClientAddressService
{
    private const CACHE_KEY = 'client_address';
    private const TTL = 3600;

    public function __construct(
        private readonly ClientAddressRepositoryInterface $clientAddressRepository,
        private readonly ClientRepositoryInterface        $clientRepository,
        private readonly CacheHelper                      $cacheHelper,
        private readonly FieldService                     $fieldService
    )
    {
    }


    /**
     * @param ClientAddressFilterDto $clientAddressFilterDto
     * @param SortDto $sortDto
     * @return PaginatedResultDto
     */
    public function getClientAddress(ClientAddressFilterDto $clientAddressFilterDto, SortDto $sortDto): PaginatedResultDto
    {
        $field = $this->fieldService
            ->setEntity(ClientAddress::class)
            ->setCacheKey(self::CACHE_KEY)
            ->getFields();

        $sort = new Sort(
            $sortDto->sort,
            $sortDto->order,
            $field
        );

        $cacheKey = $this->cacheHelper->generateKey(self::CACHE_KEY, $clientAddressFilterDto, $sort);


        try {
            $clientCacheObject= false; // = $this->cacheHelper->getCache(PaginatedResultDto::class, $cacheKey);

            if ($clientCacheObject === false) {
                $clientCacheObject = $this->findClientAddresses($clientAddressFilterDto, $sort);
                $this->cacheHelper
                    ->setTtl(self::TTL)
                    ->setCache($clientCacheObject, $cacheKey);
            }

            return $clientCacheObject;
        } catch (\Psr\Cache\CacheException|\RedisException $e) {
            return $this->findClientAddresses($clientAddressFilterDto, $sort);
        }
    }

    public function findClientAddresses(ClientAddressFilterDto $clientAddressFilterDto, Sort $sort): PaginatedResultDto
    {
        return $this->clientAddressRepository->findByClientId($clientAddressFilterDto, $sort);
    }

    public function findClientAddress(ClientAddressFilterDto $filterDto, SortDto $sortDto): array
    {
        $field = $this->fieldService
            ->setEntity(ClientAddress::class)
            ->setCacheKey(self::CACHE_KEY)
            ->getFields();

        $sort = new Sort(
            $sortDto->sort,
            $sortDto->order,
            $field
        );

        $clientAddresses = $this->clientAddressRepository->findByClientId($filterDto, $sort);

        return $clientAddresses->toApiArray();
    }

    /**
     * @param int $id
     * @param ClientAddressDto $clientAddressDto
     * @return ClientAddressDto|null
     */
    public function addClientAddress(int $id, ClientAddressDto $clientAddressDto): ?ClientAddressDto
    {
        $client = $this->clientRepository->findClientById($id);
        if (!$client) {
            throw new \RuntimeException('Client not found');
        }
        $clientAddressDto->setClient($client);
        $clientAddress = $this->clientAddressRepository->save($clientAddressDto);

        return ClientAddressDto::fromEntity($clientAddress);
    }

    /**
     * @param int $clientId
     * @param ClientAddressDto $clientAddressDto
     * @return void
     */
    public function changePrimaryAddress(int $clientId, ClientAddressDto $clientAddressDto): void
    {
        $this->clientAddressRepository->updatePrimaryAddress($clientId, $clientAddressDto);
    }

    /**
     * @param int $clientId
     * @param ClientAddressDto $clientAddressDto
     * @return ClientAddressDto|null
     */
    public function updateClientAddress(int $clientId, ClientAddressDto $clientAddressDto): ?ClientAddressDto
    {
        $client = $this->clientRepository->findClientById($clientId);
        if (!$client) {
            throw new \RuntimeException('Client not found');
        }
        $clientAddressDto->setClient($client);
        $clientAddress = $this->clientAddressRepository->save($clientAddressDto);

        $clientAddressDto = ClientAddressDto::fromEntity($clientAddress);

        $sort = new Sort(
            $sortDto->sort,
            $sortDto->order,
            $field
        );

        $cacheKey = $this->cacheHelper->generateKey(self::CACHE_KEY, $clientAddressFilterDto, $sort);

        try {
            $this->setClientCache($clientAddressDto, );
        } catch (\Exception $e) {
//            $this->loger
        }
        return $clientAddressDto;
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



    public function getAddress(int $id): ClientAddressDto
    {
        $clientAddressDto = $this->clientAddressRepository->findById($id);
        if (NULL === $clientAddressDto) {
            throw new EntityNotFoundException(sprintf('No guest found for id %d', $id));
        }

        return $clientAddressDto;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function removeClientAddress(int $id): bool
    {
        $addressClientDto = $this->getAddress($id);
        if (NULL !== $addressClientDto->getClient()) {
            $cacheKey = self::CACHE_KEY . '_' . $addressClientDto->getClient()->getId();
            $this->cacheHelper->forget($cacheKey);
        }

        return $this->clientAddressRepository->remove($addressClientDto);
    }
}

