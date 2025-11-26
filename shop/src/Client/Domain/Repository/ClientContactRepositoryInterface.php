<?php

namespace App\Client\Domain\Repository;

use App\Client\Application\Dto\ClientContactDto;
use App\Client\Application\Dto\ClientContactFilterDto;
use App\Client\Domain\Entity\Contact;
use App\Shared\Application\Dto\PaginatedResultDto;
use App\Shared\Application\ValueObject\Sort;

interface ClientContactRepositoryInterface
{
    public function findById(int $id): ?ClientContactDto;

    public function remove(Contact $contactClient): bool;

    public function save(ClientContactDto $contactClient): Contact;

    public function update(ClientContactDto $clientDto): Contact;

    public function findByClientId(int $clientId): PaginatedResultDto;

    public function findByCriteria(ClientContactFilterDto $criteria, Sort $sort): PaginatedResultDto;
}
