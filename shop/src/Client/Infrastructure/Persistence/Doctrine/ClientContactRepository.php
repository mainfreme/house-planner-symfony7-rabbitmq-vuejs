<?php

declare(strict_types=1);

namespace App\Client\Infrastructure\Persistence\Doctrine;

use App\Client\Application\Dto\ClientContactDto;
use App\Client\Application\Dto\ClientContactFilterDto;
use App\Client\Domain\Entity\Contact;
use App\Client\Domain\Repository\ClientContactRepositoryInterface;
use App\Infrastructure\Persistence\Doctrine\Paginator\DoctrineDtoPaginator;
use App\Shared\Application\Dto\PaginatedResultDto;
use App\Shared\Application\ValueObject\Sort;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;


/**
 * @extends ServiceEntityRepository<Contact>
 */
class ClientContactRepository extends ServiceEntityRepository implements ClientContactRepositoryInterface
{
    public function __construct(
        private readonly ParameterBagInterface $params,
        ManagerRegistry                        $registry,
        private EntityManagerInterface         $entityManager,
        private PropertyAccessorInterface      $accessor,
    )
    {
        parent::__construct($registry, Contact::class);
    }

    public function findById(int $id): ?ClientContactDto
    {
        $client = $this->entityManager->find(Contact::class, $id);

        return ClientContactDto::fromEntity($client);
    }

    public function findByClientId(int $clientId): PaginatedResultDto
    {
        $client = $this->createQueryBuilder('p')
            ->andWhere('p.client_id LIKE :client_id')
            ->setParameter('client_id', $clientId);


        return DoctrineDtoPaginator::paginate($client, ClientContactDto::class);
    }

    public function findByCriteria(ClientContactFilterDto $criteria, Sort $sort): PaginatedResultDto
    {
        $qb = $this->createQueryBuilder('p');

        if (!empty($criteria->getName())) {
            $qb->andWhere('LOWER(p.name) LIKE LOWER(:name)')
                ->setParameter('name', '%' . $criteria->getName() . '%');
        }
        if (!empty($criteria->getSurname())) {
            $qb->andWhere('LOWER(p.surname) LIKE LOWER(:surname)')
                ->setParameter('surname', '%' . $criteria->getSurname() . '%');
        }
        if (!empty($criteria->getEmail())) {
            $qb->andWhere('LOWER(p.email) LIKE LOWER(:email)')
                ->setParameter('email', '%' . $criteria->getEmail() . '%');
        }
        if (!empty($criteria->getPhoneNumber())) {
            $qb->andWhere('LOWER(p.phoneNumber) LIKE LOWER(:phoneNumber)')
                ->setParameter('phoneNumber', '%' . $criteria->getPhoneNumber() . '%');
        }
        if (!empty($criteria->getCountry())) {
            $qb->andWhere('LOWER(p.country) LIKE LOWER(:country)')
                ->setParameter('country', '%' . $criteria->getCountry() . '%');
        }

        if (!empty($criteria->getLanguage())) {
            $qb->andWhere('LOWER(p.language) LIKE LOWER(:language)')
                ->setParameter('language', '%' . $criteria->getLanguage() . '%');
        }

        if (!empty($criteria->getPostalCode())) {
            $qb->andWhere('p.areaCode = :areaCode')
                ->setParameter('areaCode', $criteria->getPostalCode());
        }

        return DoctrineDtoPaginator::paginate($qb, ClientContactDto::class, $sort);
    }

    public function update(ClientContactDto $clientDto): Contact
    {
        $clientContact = $this->entityManager->find(Contact::class, $clientDto->id);

        foreach (get_object_vars($clientDto) as $property => $value) {
            if ($value !== null and $property !== 'id') {
                $this->accessor->setValue($clientContact, $property, $value);
            }
        }

        $this->entityManager->persist($clientContact);
        $this->entityManager->flush();

        return $clientContact;
    }

    public function remove(Contact $contactClient): bool
    {
        try {
        $contactClient->setDeleteAt(new Carbon());
        $this->entityManager->persist($contactClient);
        $this->entityManager->flush();
        } catch (\Exception $e) {
            throw new \DataBaseException(sprintf('Nie udało się usunąć klienta %s', $contactClient->getName() .' '.$contactClient->getSurname()));
        }
    }

    public function save(ClientContactDto $contactClient): Contact
    {
        $contact = $contactClient->id
            ? $this->find($contactClient->id)
            : new Contact();
        if (!$contact) {
            throw new \RuntimeException('Contact not found');
        }

        $contact->setName($contactClient->name);
        $contact->setSurname($contactClient->surname);
        $contact->setEmail($contactClient->email);
        $contact->setPhoneNumber($contactClient->phoneNumber);
        $contact->setCountry($contactClient->country);
        $contact->setLanguage($contactClient->language);
        $contact->setAreaCode($contactClient->areaCode);
        $contact->setAddedAt($contactClient->added_at);
        $contact->setNote($contactClient->note);

        $this->entityManager->persist($contact);
        $this->entityManager->flush();

        return $contact;
    }
}
