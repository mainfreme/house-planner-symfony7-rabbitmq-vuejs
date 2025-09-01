<?php

declare(strict_types=1);

namespace App\Client\Infrastructure\Persistence\Doctrine;

use App\Client\Application\Dto\ClientDto;
use App\Client\Application\Dto\ClientFilterDto;
use App\Client\Domain\Entity\Client;
use App\Client\Domain\Repository\ClientRepositoryInterface;
use App\Infrastructure\Persistence\Doctrine\Paginator\DoctrineDtoPaginator;
use App\Shared\Application\Dto\PaginatedResultDto;
use App\Shared\Application\ValueObject\Sort;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

/**
 * @extends ServiceEntityRepository<Client>
 */
class ClientRepository extends ServiceEntityRepository implements ClientRepositoryInterface
{
    public function __construct(
        private readonly ParameterBagInterface $params,
        ManagerRegistry                        $registry,
        private EntityManagerInterface         $entityManager,
        private PropertyAccessorInterface      $accessor,
//        private LoggerInterface $logger
    )
    {

        parent::__construct($registry, Client::class);
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function findById(int $id): ?ClientDto
    {
        $client = $this->entityManager->find(Client::class, $id);

        return ClientDto::fromEntity($client);
    }

    public function findClientById(int $id): ?Client
    {
        return $this->entityManager->find(Client::class, $id);
    }

    public function checkIfExist(int $id): bool
    {
        $qb = $this->createQueryBuilder('c')
            ->select('1')
            ->where('c.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();

        return $qb !== null;
    }

    public function findByCriteria(ClientFilterDto $criteria, Sort $sort): PaginatedResultDto
    {
        $qb = $this->createQueryBuilder('c');

        if (!empty($criteria->getName())) {
            $qb->andWhere('LOWER(c.name) LIKE LOWER(:name)')
                ->setParameter('name', '%' . $criteria->getName() . '%');
        }
        if (!empty($criteria->getNip())) {
            $qb->andWhere('LOWER(c.nip) LIKE LOWER(:nip)')
                ->setParameter('nip', '%' . $criteria->getNip() . '%');
        }
        if (!empty($criteria->getRegon())) {
            $qb->andWhere('LOWER(c.regon) LIKE LOWER(:regon)')
                ->setParameter('regon', '%' . $criteria->getRegon() . '%');
        }
        if (!empty($criteria->getPesel())) {
            $qb->andWhere('LOWER(c.pesel) LIKE LOWER(:pesel)')
                ->setParameter('pesel', '%' . $criteria->getPesel() . '%');
        }
        if (!empty($criteria->getEmail())) {
            $qb->andWhere('LOWER(c.email) LIKE LOWER(:email)')
                ->setParameter('email', '%' . $criteria->getEmail() . '%');
        }
        if (!empty($criteria->getPhoneNumber())) {
            $qb->andWhere("LOWER(c.phoneNumber) LIKE LOWER(:phoneNumber)")
                ->setParameter('phoneNumber', '%' . $criteria->getPhoneNumber() . '%');
        }
        if (!empty($criteria->getCountry())) {
            $qb->andWhere('LOWER(c.country) LIKE LOWER(:country)')
                ->setParameter('country', '%' . $criteria->getCountry() . '%');
        }

        if (!empty($criteria->getIsDelete())) {
            $qb->andWhere('c.is_delete != TRUE');
        }

        $page = $criteria->getPage() ?? 1;
        $limit = $criteria->getLimit() ?? $this->params->has('app.pagination_limit')
            ? (int)$this->params->get('app.pagination_limit')
            : 10;

        return DoctrineDtoPaginator::paginate($qb, ClientDto::class, $sort, (int)$page, (int)$limit);
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

    public function update(ClientDto $clientDto): Client
    {
        $client = $this->entityManager->find(Client::class, $clientDto->id);

        foreach (get_object_vars($clientDto) as $property => $value) {
            if ($value !== null and $property !== 'id') {
                $this->accessor->setValue($client, $property, $value);
            }
        }

        $this->entityManager->persist($client);
        $this->entityManager->flush();

        return $client;
    }

    /**
     * @param Client $newClient
     * @return Client
     */
    public function save(Client $newClient): Client
    {
        $client = $newClient->getId()
            ? $this->find($newClient->getId())
            : new Client();

        if (!$client) {
            throw new \RuntimeException('Client not found');
        }

        $client->setName($newClient->getName());
        $client->setNip($newClient->getNip());
        $client->setRegon($newClient->getRegon());
        $client->setPesel($newClient->getPesel());
        $client->setEmail($newClient->getEmail());

        $client->setPhoneNumber($newClient->getPhoneNumber());
        $client->setCountry($newClient->getCountry());
        $client->setPhonePrefix($newClient->getPhonePrefix());
        $client->setIsDelete($newClient->getIsDelete());

        $this->entityManager->persist($client);
        $this->entityManager->flush();

        return $client;
    }
}
