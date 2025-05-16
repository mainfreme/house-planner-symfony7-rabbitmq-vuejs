<?php

namespace App\Infrastructure\Persistence\Doctrine\History;

use App\Domain\History\Entity\History;
use App\Domain\History\Repository\HistoryRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

#[AsService]
class HistoryRepository extends ServiceEntityRepository implements HistoryRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, History::class);
    }

}
