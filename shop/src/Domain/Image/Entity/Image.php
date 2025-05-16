<?php

namespace App\Domain\Image\Entity;

use App\Domain\Image\Repository\ImageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ImageRepository::class)]
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
}
