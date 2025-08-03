<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Paginator;

use App\Application\Shared\Dto\ArrayMappableDtoInterface;
use App\Application\Shared\Dto\PaginatedResultDto;
use Doctrine\ORM\QueryBuilder;

final class DoctrinePaginator
{

    public static function paginate(
        QueryBuilder $qb,
        string $dtoClass,
        int $page = 1,
        int $limit = 10,
    ): PaginatedResultDto
    {
        if (!is_subclass_of($dtoClass, ArrayMappableDtoInterface::class)) {
            throw new \InvalidArgumentException("DTO class must implement ArrayMappableDtoInterface.");
        }

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


        $items = array_map(function ($dto) use ($dtoClass) {
            if (is_array($dto)) {
                return $dtoClass::fromArray($dto);
            }

            if (is_object($dto)) {
                return $dtoClass::fromEntity($dto);
            }

            throw new \InvalidArgumentException('Nieobsługiwany typ danych w paginatorze');

        }, $data);


        return new PaginatedResultDto(
            total: $total,
            page: $page,
            limit: $limit,
            pages: (int)ceil($total / $limit),
            items: $items,
        );
    }
}
