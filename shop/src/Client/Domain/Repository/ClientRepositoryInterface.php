<?php

declare(strict_types=1);

namespace App\Client\Domain\Repository;

use App\Client\Application\Dto\ClientDto;
use App\Client\Application\Dto\ClientFilterDto;
use App\Client\Domain\Entity\Client;
use App\Shared\Application\Dto\PaginatedResultDto;
use App\Shared\Application\ValueObject\Sort;

interface ClientRepositoryInterface
{
    public function findById(int $id): ?ClientDto;

    public function remove(Client $client): bool;

    public function save(Client $newClient): Client;

    public function update(ClientDto $clientDto): Client;

    public function findByCriteria(ClientFilterDto $criteria, Sort $sort): PaginatedResultDto;
}
