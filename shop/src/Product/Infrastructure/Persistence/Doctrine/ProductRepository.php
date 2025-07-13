<?php

declare(strict_types=1);

namespace App\Product\Infrastructure\Persistence\Doctrine;

use App\Application\Shared\Dto\PaginatedResultDto;
use App\Product\Domain\Entity\Product;
use App\Product\Domain\Entity\ProductType;
use App\Product\Domain\Repository\ProductRepositoryInterface;
use App\Infrastructure\Persistence\Doctrine\Paginator\DoctrinePaginator;
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

    public function findByCriteria(array $criteria): PaginatedResultDto
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

        if (!empty($criteria['priceMin'])) {
            $qb->andWhere('p.price >= :priceMin')
                ->setParameter('priceMin', $criteria['priceMin']);
        }

        if (!empty($criteria['priceMax'])) {
            $qb->andWhere('p.price <= :priceMax')
                ->setParameter('priceMax', $criteria['priceMax']);
        }

        if (!empty($criteria['isActive'])) {
            $qb->andWhere('p.is_active = :active')
                ->setParameter('active', $criteria['isActive']);
        }

        if (!empty($criteria['page'])) {
            $qb
                ->setFirstResult(($criteria['page'] - 1) * 10)
                ->setMaxResults(10);
        }

        $page = $criteria['page'] ?? 1;
        $limit = $criteria['limit'] ?? 5;

        return DoctrinePaginator::paginate($qb, $page, $limit);
    }

    public function findMinMaxPrice(string $category = '', bool $active = true): array
    {
        $qb = $this->createQueryBuilder('p')
            ->select('MIN(p.price) as min_price, MAX(p.price) as max_price')
            ->where('p.is_active = :active')
            ->setParameter('active', $active);

        if (!empty($category)) {
            $qb
                ->leftJoin('p.type', 'pt')
                ->andWhere('pt.name = :product_type_name')
                ->setParameter('product_type_name', ucfirst($category));
        }

        return $qb->getQuery()
            ->getSingleResult();
    }

}
