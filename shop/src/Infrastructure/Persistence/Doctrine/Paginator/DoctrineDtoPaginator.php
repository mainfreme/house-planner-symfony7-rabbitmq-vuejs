<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Paginator;

use App\Shared\Application\Dto\ArrayMappableInterface;
use App\Shared\Application\Dto\PaginatedResultDto;
use App\Shared\Application\ValueObject\Sort;
use Doctrine\ORM\QueryBuilder;

final class DoctrineDtoPaginator
{

    public static function paginate(
        QueryBuilder $qb,
        string $dtoClass,
        Sort $sort,
        int $page = 1,
        int $limit = 10,
    ): PaginatedResultDto
    {

        $rootAliases = $qb->getRootAliases();
        $alias = $rootAliases[0].'.';

        $countQb = clone $qb;
        $total = self::countTotalRow($countQb);

        $data = $qb->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->orderBy($alias.$sort->field(), $sort->direction())
            ->getQuery()
            ->getResult();

        $items = self::packageToDto($data, $dtoClass);


        return new PaginatedResultDto(
            total: $total,
            page: $page,
            limit: $limit,
            pages: (int)ceil($total / $limit),
            items: $items,
        );
    }

    private static function packageToDto($data, string $dtoClass): array
    {
        if (!is_subclass_of($dtoClass, ArrayMappableInterface::class)) {
            throw new \InvalidArgumentException("DTO class must implement ArrayMappableDtoInterface.");
        }

        return array_map(function (mixed $row) use ($dtoClass) {
            return match (true) {
                is_array($row) => $dtoClass::fromArray($row),
                is_object($row) => $dtoClass::fromEntity($row),
                default => throw new \InvalidArgumentException('Unsupported data type in '.self::class),
            };
        }, $data);
    }

    private static function countTotalRow(QueryBuilder $countQb): int
    {
        $result = $countQb
            ->select('COUNT(DISTINCT ' . $countQb->getRootAliases()[0] . '.id) AS total')
            ->getQuery()
            ->getOneOrNullResult();

        return (int) ($result['total'] ?? 0);
    }
}
