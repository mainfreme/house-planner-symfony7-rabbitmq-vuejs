<?php

declare(strict_types=1);

namespace App\Image\Domain\Entity;


use App\Image\Infrastructure\Persistence\Doctrine\ProductImagesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductImagesRepository::class)]
class ProductImages
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: self::class)]
    private ?self $Image = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImage(): ?self
    {
        return $this->Image;
    }

    public function setImage(?self $Image): static
    {
        $this->Image = $Image;

        return $this;
    }
}
