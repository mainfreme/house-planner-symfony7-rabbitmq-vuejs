<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Paginator;

use App\Product\Application\Dto\ProductDto;
use App\Application\Shared\Dto\PaginatedResultDto;
use Doctrine\ORM\QueryBuilder;

final class DoctrinePaginator
{

    public static function paginate(QueryBuilder $qb, int $page = 1, int $limit = 10): PaginatedResultDto
    {
        $countQb = clone $qb;
        $result = $countQb
            ->select('COUNT(DISTINCT ' . $qb->getRootAliases()[0] . '.id)')
            ->getQuery()
            ->getOneOrNullResult();

        $total = (int) ($result['1'] ?? 0);

        $data = $qb->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();

        $items = array_map(
            fn(array $product) => ProductDto::fromArray($product),
            $data
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
