<?php

namespace App\Infrastructure\Persistence\Doctrine\Image;

use App\Domain\Image\Entity\ProductImages;
use App\Domain\Image\Repository\ProductImageRepositoryInterface;
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
