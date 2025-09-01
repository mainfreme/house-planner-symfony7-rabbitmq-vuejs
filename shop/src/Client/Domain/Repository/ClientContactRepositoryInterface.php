<?php

namespace App\Client\Domain\Repository;

use App\Client\Application\Dto\ClientContactArray;
use App\Client\Domain\Entity\Contact;
use App\Shared\Application\Dto\PaginatedResultDto;

interface ClientContactRepositoryInterface
{
    public function findById(int $id): ?ClientContactArray;

    public function remove(Contact $contactClient): bool;

    public function save(Contact $contactClient): bool;

    public function findByClientId(int $clientId): PaginatedResultDto;

    public function findByCriteria(array $criteria): PaginatedResultDto;
}
