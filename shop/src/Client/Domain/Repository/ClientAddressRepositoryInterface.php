<?php

declare(strict_types=1);

namespace App\Client\Domain\Repository;

use App\Client\Application\Dto\ClientAddressDto;
use App\Client\Application\Dto\ClientAddressFilterDto;
use App\Client\Domain\Entity\ClientAddress;
use App\Shared\Application\Dto\PaginatedResultDto;
use App\Shared\Application\ValueObject\Sort;

interface ClientAddressRepositoryInterface
{
    public function findById(int $id): ?ClientAddressDto;

    public function remove(ClientAddressDto $clientAddressDto): bool;

    public function save(ClientAddressDto $clientAddress): ClientAddress;

    public function findByClientId(ClientAddressFilterDto $filterDto, Sort $sort): PaginatedResultDto;

    public function findByCriteria(array $criteria): PaginatedResultDto;

    public function updatePrimaryAddress(int $clientId, ClientAddressDto $clientAddressDto): bool;
}
