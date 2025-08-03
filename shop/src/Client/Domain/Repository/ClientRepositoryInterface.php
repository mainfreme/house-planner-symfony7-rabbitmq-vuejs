<?php

declare(strict_types=1);

namespace App\Client\Domain\Repository;

use App\Application\Shared\Dto\PaginatedResultDto;
use App\Client\Domain\Entity\Client;

interface ClientRepositoryInterface
{
    public function findById(int $id): ?Client;

    public function remove(Client $client): bool;

    public function save(Client $product): bool;

    public function findByCriteria(array $criteria): PaginatedResultDto;
}
