<?php

declare(strict_types=1);

namespace App\Image\Domain\Entity;

use App\Product\Domain\Entity\Product;
use App\Image\Infrastructure\Persistence\Doctrine\ImageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity(repositoryClass: ImageRepository::class)]
#[ORM\Table(name: "image")]
class Image
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $data = null;

    #[ORM\Column]
    private array $property = [];

    #[ORM\Column]
    private ?bool $is_delete = null;

    #[ORM\Column(nullable: true)]
    private ?bool $is_active = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product = null;

    #[ORM\Column(type: 'uuid')]
    private ?UuidInterface $uuid = null;

    #[ORM\Column(nullable: true)]
    private ?bool $is_main = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getData(): ?string
    {
        return $this->data;
    }

    public function setData(string $data): static
    {
        $this->data = $data;

        return $this;
    }

    public function getProperty(): array
    {
        return $this->property;
    }

    public function setProperty(array $property): static
    {
        $this->property = $property;

        return $this;
    }

    public function isDelete(): ?bool
    {
        return $this->is_delete;
    }

    public function setIsDelete(bool $is_delete): static
    {
        $this->is_delete = $is_delete;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->is_active;
    }

    public function setIsActive(?bool $is_active): static
    {
        $this->is_active = $is_active;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): static
    {
        $this->product = $product;

        return $this;
    }

    public function getUuid(): ?UuidInterface
    {
        return $this->uuid;
    }

    public function setUuid(UuidInterface $uuid): static
    {
        $this->uuid = $uuid;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getIsMain(): ?bool
    {
        return $this->is_main;
    }

    /**
     * @param bool|null $is_main
     */
    public function setIsMain(?bool $is_main): void
    {
        $this->is_main = $is_main;
    }
}
