<?php

namespace App\Domain\File\Entity;

use App\Domain\File\Enum\FileEnum;
use App\Repository\FileRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

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
    private ?string $path = null;

    #[ORM\Column(type: Types::BIGINT)]
    private ?string $size_bytes = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    private ?int $owner = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getFileName(): ?string
    {
        return $this->file_name;
    }

    public function setFileName(string $file_name): static
    {
        $this->file_name = $file_name;

        return $this;
    }

    public function getFileType(): ?FileEnum
    {
        return $this->file_type;
    }

    public function setFileType(FileEnum $file_type): static
    {
        $this->file_type = $file_type;

        return $this;
    }

    public function getRelatedId(): ?int
    {
        return $this->related_id;
    }

    public function setRelatedId(?int $related_id): static
    {
        $this->related_id = $related_id;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): static
    {
        $this->path = $path;

        return $this;
    }

    public function getSizeBytes(): ?string
    {
        return $this->size_bytes;
    }

    public function setSizeBytes(string $size_bytes): static
    {
        $this->size_bytes = $size_bytes;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getOwner(): ?int
    {
        return $this->owner;
    }

    public function setOwner(int $owner): static
    {
        $this->owner = $owner;

        return $this;
    }
}
