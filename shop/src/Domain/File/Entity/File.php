<?php

namespace App\Domain\File\Entity;

use App\Domain\File\Enum\FileEnum;

use App\Domain\Product\Entity\Product;
use App\Repository\FileRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

#[ORM\Entity(repositoryClass: FileRepository::class)]
class File
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $file_name = null;

    #[ORM\Column(enumType: FileEnum::class)]
    private ?FileEnum $file_type = null;

    #[ORM\Column(nullable: true)]
    private ?int $related_id = null;

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
    private ?Uuid $uuid = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getFileName(): ?string
    {
        return $this->file_name;
    }

    /**
     * @param string|null $file_name
     */
    public function setFileName(?string $file_name): void
    {
        $this->file_name = $file_name;
    }

    /**
     * @return FileEnum|null
     */
    public function getFileType(): ?FileEnum
    {
        return $this->file_type;
    }

    /**
     * @param FileEnum|null $file_type
     */
    public function setFileType(?FileEnum $file_type): void
    {
        $this->file_type = $file_type;
    }

    /**
     * @return int|null
     */
    public function getRelatedId(): ?int
    {
        return $this->related_id;
    }

    /**
     * @param int|null $related_id
     */
    public function setRelatedId(?int $related_id): void
    {
        $this->related_id = $related_id;
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

    public function getUuid(): ?Uuid
    {
        return $this->uuid;
    }

    public function setUuid(Uuid $uuid): static
    {
        $this->uuid = $uuid;

        return $this;
    }
}
