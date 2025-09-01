<?php

declare(strict_types=1);

namespace App\Client\Infrastructure\Persistence\Doctrine;

use App\Client\Application\Dto\ClientContactArray;
use App\Client\Domain\Entity\Contact;
use App\Client\Domain\Repository\ClientContactRepositoryInterface;
use App\Infrastructure\Persistence\Doctrine\Paginator\DoctrineDtoPaginator;
use App\Shared\Application\Dto\PaginatedResultDto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;


/**
 * @extends ServiceEntityRepository<Contact>
 */
class ClientContactRepository extends ServiceEntityRepository implements ClientContactRepositoryInterface
{
    public function __construct(
        private readonly ParameterBagInterface $params,
        ManagerRegistry $registry,
        private EntityManagerInterface $entityManager,
//        private LoggerInterface $logger
    )
    {
        parent::__construct($registry, Contact::class);
    }

    public function findById(int $id): ?ClientContactArray
    {
        $client = $this->entityManager->find(Contact::class, $id);

        return ClientContactArray::fromEntity($client);
    }

    public function findByClientId(int $clientId): PaginatedResultDto
    {
        $client = $this->createQueryBuilder('p')
            ->andWhere('p.client_id LIKE :client_id')
            ->setParameter('client_id', $clientId);



        return DoctrineDtoPaginator::paginate($client, ClientContactArray::class);
    }

    public function remove(Contact $contactClient): bool
    {
        return true;
    }

    public function save(Contact $contactClient): bool
    {
        $this->entityManager->persist($contactClient);
        $this->entityManager->flush();
    }

    public function findByCriteria(array $criteria): PaginatedResultDto
    {
        $qb = $this->createQueryBuilder('p');

        if (!empty($criteria['name'])) {
            $qb->andWhere('p.name LIKE :name')
                ->setParameter('name', '%' . $criteria['name'] . '%');
        }
        if (!empty($criteria['surname'])) {
            $qb->andWhere('p.surname LIKE :surname')
                ->setParameter('surname', '%' .$criteria['surname'] . '%');
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
            $qb->andWhere('p.country LIKE :country')
                ->setParameter('country', '%' .$criteria['country'] . '%');
        }

        if (!empty($criteria['language'])) {
            $qb->andWhere('p.language LIKE :language')
                ->setParameter('language', '%' .$criteria['language'] . '%');
        }

        if (!empty($criteria['areaCode'])) {
            $qb->andWhere('p.areaCode = :areaCode')
                ->setParameter('areaCode', $criteria['areaCode']);
        }

        $page = $criteria['page'] ?? 1;
        $limit = $criteria['limit'] ?? $this->params->has('app.pagination_limit')
            ? (int) $this->params->get('app.pagination_limit')
            : 10;

        return DoctrineDtoPaginator::paginate($qb, ClientContactArray::class, (int)$page, (int)$limit);
    }
}
