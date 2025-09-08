<?php

declare(strict_types=1);

namespace App\Client\Infrastructure\Persistence\Doctrine;


use App\Client\Application\Dto\ClientAddressDto;
use App\Client\Application\Dto\ClientAddressFilterDto;
use App\Client\Domain\Entity\ClientAddress;
use App\Client\Domain\Repository\ClientAddressRepositoryInterface;
use App\Infrastructure\Persistence\Doctrine\Paginator\DoctrineDtoPaginator;
use App\Shared\Application\Dto\PaginatedResultDto;
use App\Shared\Application\ValueObject\Sort;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * @extends ServiceEntityRepository<ClientAddress>
 */
class ClientAddressRepository extends ServiceEntityRepository implements ClientAddressRepositoryInterface
{
    public function __construct(
        private readonly ParameterBagInterface $params,
        ManagerRegistry                        $registry,
        private EntityManagerInterface         $entityManager,
//        private LoggerInterface $logger
    )
    {
        parent::__construct($registry, ClientAddress::class);
    }


    public function findById(int $id): ?ClientAddressDto
    {
        $client = $this->entityManager->find(ClientAddress::class, $id);

        return ClientAddressDto::fromEntity($client);
    }

    public function findByClientId(ClientAddressFilterDto $filterDto, Sort $sort): PaginatedResultDto
    {
        $qb = $this->createQueryBuilder('a')
            ->join('a.client', 'c')
            ->where('c.id = :clientId')
            ->setParameter('clientId', $filterDto->getClientId());

        if ($filterDto->getStreet()) {
            $qb->andWhere('LOWER(a.street) LIKE LOWER(:street)')
                ->setParameter('street', '%' . $filterDto->getStreet() . '%');
        }

        if ($filterDto->getPostalCode()) {
            $qb->andWhere('LOWER(a.postal_code) LIKE LOWER(:postal_code)')
                ->setParameter('postal_code', '%' . $filterDto->getPostalCode() . '%');
        }

        if ($filterDto->getCity()) {
            $qb->andWhere('LOWER(a.city) LIKE LOWER(:city)')
                ->setParameter('city', '%' . $filterDto->getCity() . '%');
        }

        if ($filterDto->getStateProvince()) {
            $qb->andWhere('LOWER(a.state_province) LIKE LOWER(:state_province)')
                ->setParameter('state_province', '%' . $filterDto->getStateProvince() . '%');
        }

        if ($filterDto->getCountry()) {
            $qb->andWhere('LOWER(a.country) LIKE LOWER(:country)')
                ->setParameter('country', '%' . $filterDto->getCountry() . '%');
        }

        if ($filterDto->getAdditionalInfo()) {
            $qb->andWhere("LOWER(a.additional_info) LIKE LOWER(:additional_info)")
                ->setParameter('additional_info', '%' . $filterDto->getAdditionalInfo() . '%');
        }

        if ($filterDto->getHouseNumber()) {
            $qb->andWhere('LOWER(a.house_number) LIKE LOWER(:house_number)')
                ->setParameter('house_number', '%' . $filterDto->getHouseNumber() . '%');
        }

        if ($filterDto->getApartmentNumber()) {
            $qb->andWhere('LOWER(a.apartment_number) LIKE LOWER(:apartment_number)')
                ->setParameter('apartment_number', '%' . $filterDto->getApartmentNumber() . '%');
        }

        if ($filterDto->getIsPrimary() !== null) {
            $qb->andWhere('a.is_primary = :is_primary')
                ->setParameter('is_primary', $filterDto->getIsPrimary());
        }

        if ($filterDto->getAddedFrom() && $filterDto->getAddedTo()) {
            try {
                $addedFromDate = new \DateTimeImmutable($filterDto->getAddedFrom());
                $addedToDate = new \DateTimeImmutable($filterDto->getAddedTo());

                $qb->andWhere('a.added_at >= :start_date AND a.added_at <= :end_date')
                    ->setParameter('start_date', $addedFromDate->setTime(0, 0, 0))
                    ->setParameter('end_date', $addedToDate->setTime(23, 59, 59));

            } catch (\Exception $e) {
                // Opcjonalnie: obsłuż błąd, jeśli format daty w DTO jest nieprawidłowy
            }
        } elseif ($filterDto->getAddedFrom()) {
            try {
                $addedFromDate = new \DateTimeImmutable($filterDto->getAddedFrom());
                $qb->andWhere('a.added_at >= :start_date')
                    ->setParameter('start_date', $addedFromDate->setTime(0, 0, 0));
            } catch (\Exception $e) {
                // Obsłuż błąd formatu daty
            }
        } elseif ($filterDto->getAddedTo()) {
            try {
                $addedToDate = new \DateTimeImmutable($filterDto->getAddedTo());
                $qb->andWhere('a.added_at <= :end_date')
                    ->setParameter('end_date', $addedToDate->setTime(23, 59, 59));
            } catch (\Exception $e) {
                // Obsłuż błąd formatu daty
            }
        }


        $page = $filterDto->getPage() ?? 1;
        $limit = $this->params->has('app.pagination_limit')
            ? (int)$this->params->get('app.pagination_limit')
            : 10;

        return DoctrineDtoPaginator::paginate($qb, ClientAddressDto::class, $sort, (int)$page, (int)$limit);
    }

    public function remove(ClientAddressDto $clientAddressDto): bool
    {
        $entityManager = $this->getEntityManager();
        $entityManager->beginTransaction();

        $clientAddress = $entityManager->find(ClientAddress::class, $clientAddressDto->id);
        try {
            $entityManager->remove($clientAddress);
            $entityManager->flush();
            $entityManager->commit();

            return true;
        } catch (\Exception $e) {
            $entityManager->rollback();
//            $this->logger->error('Błąd podczas operacji soft delete: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * @param ClientAddressDto $clientAddress
     * @return ClientAddress
     */
    public function save(ClientAddressDto $clientAddress): ClientAddress
    {
        $address = $clientAddress->id
            ? $this->find($clientAddress->id)
            : new ClientAddress();

        if (!$address) {
            throw new \RuntimeException(sprintf('ClientAddress with ID %d not found', $clientAddress->id));
        }

        $address->setStreet($clientAddress->street);
        $address->setPostalCode($clientAddress->postal_code);
        $address->setCity($clientAddress->city);
        $address->setStateProvince($clientAddress->state_province);
        $address->setCountry($clientAddress->country);
        $address->setAdditionalInfo($clientAddress->additional_info);
        $address->setHouseNumber($clientAddress->house_number);
        $address->setApartmentNumber($clientAddress->apartment_number);
        $address->setIsPrimary($clientAddress->is_primary);
        $address->setAddedAt($clientAddress->added_at ?? new \DateTimeImmutable());
        $address->setClient($clientAddress->client);

        $this->entityManager->persist($address);
        $this->entityManager->flush();

        return $address;
    }

    public function findByCriteria(array $criteria): PaginatedResultDto
    {
        // TODO: Implement findByCriteria() method.
    }

    public function updatePrimaryAddress(int $clientId, ClientAddressDto $clientAddressDto): bool
    {
        $qb = $this->entityManager->createQueryBuilder();
        $this->entityManager->beginTransaction();
        try {
            /* @todo  do poprawy */
            $qb->update(ClientAddress::class, 'e')
                ->set('e.isPrimary', ':isPrimary')
                ->andWhere('e.client = :clientId')
                ->andWhere('e.id = :id')
                ->setParameter('isPrimary', $clientAddressDto->is_primary)
                ->setParameter('clientId', $clientId)
                ->setParameter('id', $clientAddressDto->id)
                ->getQuery()
                ->execute();

            $qb->update(ClientAddress::class, 'e')
                ->set('e.isPrimary', ':isPrimary')
                ->where('e.client = :clientId')
                ->andWhere('e.id = :id')
                ->setParameter('isPrimary', !$clientAddressDto->is_primary)
                ->setParameter('clientId', $clientId)
                ->setParameter('id', $clientAddressDto->id)
                ->getQuery()
                ->execute();

            $this->entityManager->commit();
            return true;
        } catch (\Throwable $e) {
            $this->entityManager->rollback();
////            $this->logger->error('Błąd podczas operacji soft delete: ' . $e->getMessage());
            return false;
        }
    }
}
