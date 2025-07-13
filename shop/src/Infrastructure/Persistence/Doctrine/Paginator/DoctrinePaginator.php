<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Paginator;

use App\Application\Product\Dto\ProductDto;
use App\Application\Shared\Dto\PaginatedResultDto;
use App\Domain\Product\Entity\Product;
use Doctrine\ORM\QueryBuilder;

final class DoctrinePaginator
{

    public static function paginate(QueryBuilder $qb, int $page = 1, int $limit = 10): PaginatedResultDto
    {
        $countQb = clone $qb;
        $countQb->select('COUNT(DISTINCT ' . $qb->getRootAliases()[0] . '.id)');

        $total = (int)$countQb->getQuery()->getSingleScalarResult();

        $qb->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);

        $products = $qb->getQuery()->getResult();

        $items = array_map(
            fn(Product $product) => ProductDto::fromEntity($product),
            $products
        );

        return new PaginatedResultDto(
            total: $total,
            page: $page,
            limit: $limit,
            pages: (int)ceil($total / $limit),
            items: $items,
        );
    }
}
