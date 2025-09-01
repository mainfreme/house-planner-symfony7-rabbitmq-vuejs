<?php

declare(strict_types=1);

namespace App\Product\Infrastructure\Persistence\Doctrine;

use App\Image\Domain\Entity\Image;
use App\Infrastructure\Persistence\Doctrine\Paginator\DoctrineDtoPaginator;
use App\Product\Application\Dto\ProductArray;
use App\Product\Application\Dto\ProductFilterDto;
use App\Product\Domain\Entity\Product;
use App\Product\Domain\Entity\ProductType;
use App\Product\Domain\Repository\ProductRepositoryInterface;
use App\Shared\Application\Dto\PaginatedResultDto;
use App\Shared\Application\ValueObject\Sort;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

#[AsService]
class ProductRepository extends ServiceEntityRepository implements ProductRepositoryInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(
        ManagerRegistry $registry,
        EntityManagerInterface $entityManager,
        private readonly ParameterBagInterface $params
    )
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

    public function findByCriteria(ProductFilterDto $criteria, Sort $sort): PaginatedResultDto
    {
        $qb = $this->createQueryBuilder('p')
            ->leftJoin('p.type', 'pt')
            ->leftJoin(Image::class, 'i', 'WITH', 'i.product = p AND i.is_main = true AND i.is_delete = false')
            ->addSelect('i.data, i.property, i.uuid')
        ;

        if (!empty($criteria->getName())) {
            $qb->andWhere('p.name iLIKE :name')
                ->setParameter('name', '%' . $criteria->getName() . '%');
        }
//        if (!empty($criteria['category'])) {
//            if (!empty($criteria->getCategory())) {

//            $qb
//                ->andWhere('pt.link = :product_type_link')
//                ->setParameter('product_type_link', strtolower($criteria['category']))
//            ;
//        }

        if (!empty($criteria->getPriceMin())) {
            $qb->andWhere('p.price >= :priceMin')
                ->setParameter('priceMin', $criteria->getPriceMin());
        }

        if (!empty($criteria->getPriceMax())) {
            $qb->andWhere('p.price <= :priceMax')
                ->setParameter('priceMax', $criteria->getPriceMax());
        }

        if (!empty($criteria->getIsActive())) {
            $qb->andWhere('p.is_active = :active')
                ->setParameter('active', $criteria->getIsActive());
        }

        $page = $criteria->getPage() ?? 1;
        $limit = $criteria->getLimit() ?? $this->params->has('app.pagination_limit')
            ? (int) $this->params->get('app.pagination_limit')
            : 10;

        return DoctrineDtoPaginator::paginate($qb, ProductArray::class, $sort, (int)$page, (int)$limit);
    }

    public function findMinMaxPrice(string $category = '', bool $active = true): array
    {
        $qb = $this->createQueryBuilder('p')
            ->select('MIN(p.price) as min_price, MAX(p.price) as max_price')
            ->where('p.is_active = :active')
            ->setParameter('active', $active);

        if (!empty($category)) {
            $qb
                ->innerJoin('p.type', 'pt')
                ->andWhere('pt.link = :product_type_link')
                ->setParameter('product_type_link', strtolower($category))
            ;
        }

        return $qb->getQuery()
            ->getOneOrNullResult(Query::HYDRATE_SCALAR);
    }

}
