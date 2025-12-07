<?php

declare(strict_types=1);

namespace App\Image\Domain\Entity;

use App\Image\Domain\Entity\Image;
use App\Image\Infrastructure\Persistence\Doctrine\ProductImagesRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity(repositoryClass: ProductImagesRepository::class)]
class ProductImages
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid')]
    private UuidInterface $uuid;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'image_uuid')]
    private ?Image $Image = null;

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function setUuid(UuidInterface $uuid): static
    {
        $this->uuid = $uuid;

        return $this;
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
