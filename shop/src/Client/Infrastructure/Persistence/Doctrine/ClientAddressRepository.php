<?php

declare(strict_types=1);

namespace App\Client\Infrastructure\Persistence\Doctrine;


use App\Client\Domain\Entity\ClientAddress;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ClientAddress>
 */
class ClientAddressRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ClientAddress::class);
    }


}
