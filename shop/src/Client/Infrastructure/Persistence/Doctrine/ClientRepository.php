<?php

declare(strict_types=1);

namespace App\Client\Infrastructure\Persistence\Doctrine;

use App\Application\Shared\Dto\PaginatedResultDto;
use App\Client\Application\Dto\ClientDto;
use App\Client\Domain\Entity\Client;
use App\Client\Domain\Repository\ClientRepositoryInterface;
use App\Infrastructure\Persistence\Doctrine\Paginator\DoctrinePaginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * @extends ServiceEntityRepository<Client>
 */
class ClientRepository extends ServiceEntityRepository implements ClientRepositoryInterface
{
    public function __construct(
        private readonly ParameterBagInterface $params,
        ManagerRegistry $registry,
        private EntityManagerInterface $entityManager,
//        private LoggerInterface $logger
    )
    {

        parent::__construct($registry, Client::class);
    }

    public function findById(int $id): ?Client
    {
        return $this->entityManager->find(Client::class, $id);
    }

    public function findByCriteria(array $criteria): PaginatedResultDto
    {
        $qb = $this->createQueryBuilder('p');

        if (!empty($criteria['name'])) {
            $qb->andWhere('p.name LIKE :name')
                ->setParameter('name', '%' . $criteria['name'] . '%');
        }
        if (!empty($criteria['nip'])) {
            $qb->andWhere('p.nip LIKE :nip')
                ->setParameter('nip', '%' .$criteria['nip'] . '%');
        }
        if (!empty($criteria['regon'])) {
            $qb->andWhere('p.regon LIKE :regon')
                ->setParameter('regon', '%' .$criteria['regon'] . '%');
        }
        if (!empty($criteria['pesel'])) {
            $qb->andWhere('p.pesel LIKE :pesel')
                ->setParameter('pesel', '%' .$criteria['pesel'] . '%');
        }
        if (!empty($criteria['email'])) {
            $qb->andWhere('p.email LIKE :email')
                ->setParameter('email', '%' .$criteria['email'] . '%');
        }

        if (!empty($criteria['phoneNumber'])) {
            $qb->andWhere('p.phoneNumber LIKE :phoneNumber')
                ->setParameter('phoneNumber', '%' .$criteria['phoneNumber'] . '%');
        }

        if (!empty($criteria['country'])) {
            $qb->andWhere('p.country = :country')
                ->setParameter('country', $criteria['country']);
        }

        if (!empty($criteria['is_delete'])) {
            $qb->andWhere('p.is_delete = FALSE');
        } else {
            $qb->andWhere('p.is_delete != TRUE');
        }

        $page = $criteria['page'] ?? 1;
        $limit = $criteria['limit'] ?? $this->params->has('app.pagination_limit')
            ? (int) $this->params->get('app.pagination_limit')
            : 10;

        return DoctrinePaginator::paginate($qb, ClientDto::class, (int)$page, (int)$limit);
    }

    public function remove(Client $client): bool
    {
        $entityManager = $this->getEntityManager();
        $entityManager->beginTransaction();

        try {
            $client->setIsDelete(true);
            $entityManager->persist($client);
            $entityManager->flush();
            $entityManager->commit();

            return true;
        } catch (\Exception $e) {
            $entityManager->rollback();
//            $this->logger->error('Błąd podczas operacji soft delete: ' . $e->getMessage());
            return false;
        }
    }

    public function save(Client $product): bool
    {
        return true;
    }
}
