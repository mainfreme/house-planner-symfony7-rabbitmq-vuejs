<?php

declare(strict_types=1);

namespace App\Image\Infrastructure\Persistence\Doctrine;

use App\Image\Domain\Entity\ProductImages;
use App\Image\Domain\Repository\ProductImageRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductImages>
 */
class ProductImagesRepository extends ServiceEntityRepository implements ProductImageRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductImages::class);
    }
}
