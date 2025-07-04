<?php

namespace App\Infrastructure\Persistence\Doctrine\Product;

use App\Domain\Product\Entity\ProductType;
use App\Domain\Product\Repository\TypeProductRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

#[AsService]
class ProductTypeRepository extends ServiceEntityRepository implements TypeProductRepositoryInterface
{

    private EntityManagerInterface $entityManager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, ProductType::class);
        $this->entityManager = $entityManager;
    }

    public function save(ProductType $typeProduct): void
    {
        $this->entityManager->persist($typeProduct);
        $this->entityManager->flush();
    }

    public function findById(int $id): ?ProductType
    {
        return $this->entityManager->find(ProductType::class, $id);
    }

    public function findByName(string $name): ?ProductType
    {
        return $this->entityManager
            ->getRepository(ProductType::class)
            ->findOneBy(['name' => $name]);
    }

    public function findAll(): array
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Pobiera wszystkie aktywne typy produktów.
     *
     * @return ProductType[]
     */
    public function findActive(): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.is_public = :is_public')
            ->setParameter('is_public', true)
            ->orderBy('p.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function remove(ProductType $typeProduct): void
    {
        $this->entityManager->remove($typeProduct);
        $this->entityManager->flush();
    }
}
