<?php

namespace App\Infrastructure\Persistence\Doctrine\Product;

use App\Domain\Product\Entity\Product;
use App\Domain\Product\Entity\ProductType;
use App\Domain\Product\Repository\ProductRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

#[AsService]
class ProductRepository extends ServiceEntityRepository implements ProductRepositoryInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Product::class);
        $this->entityManager = $entityManager;
    }

    public function save(Product $product): void
    {
        $this->entityManager->persist($product);
        $this->entityManager->flush();
    }

    public function findById(int $id): ?Product
    {
        return $this->entityManager->find(Product::class, $id);
    }

    public function findByType(ProductType $typeProduct): array
    {
        return $this->entityManager
            ->getRepository(Product::class)
            ->findBy(['type_id' => $typeProduct->getId()]);
    }

    public function findByName(string $name): ?Product
    {
        return $this->entityManager
            ->getRepository(Product::class)
            ->findOneBy(['name' => $name]);
    }

    public function findAll(): array
    {
        return $this->entityManager
            ->getRepository(Product::class)
            ->findAll();
    }

    public function remove(Product $product): void
    {
        $this->entityManager->remove($product);
        $this->entityManager->flush();
    }

    public function findByCriteria(array $criteria): array
    {
        $qb = $this->createQueryBuilder('p')
            ->leftJoin('p.type', 'pt');

        if (!empty($criteria['name'])) {
            $qb->andWhere('p.name LIKE :name')
                ->setParameter('name', '%' . $criteria['name'] . '%');
        }

        if (!empty($criteria['type'])) {
            $qb->andWhere('pt.name = :pt_name')
                ->setParameter('pt_name', $criteria['type']);
        }

        if (!empty($criteria['price_min'])) {
            $qb->andWhere('p.price >= :price_min')
                ->setParameter('price_min', $criteria['price_min']);
        }

        if (!empty($criteria['price_max'])) {
            $qb->andWhere('p.price <= :price_max')
                ->setParameter('price_max', $criteria['price_max']);
        }

        if ($criteria['is_active'] !== null) {
            $qb->andWhere('p.is_active = :is_active')
                ->setParameter('is_active', $criteria['is_active']);
        }

        return $qb->getQuery()->getResult();
    }

}
