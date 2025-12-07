<?php

namespace App\Domain\Template\Entity;

use App\Domain\File\Entity\File;
use App\Domain\Template\Enum\TemplateEnum;
use App\Repository\TemplateRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity(repositoryClass: TemplateRepository::class)]
class Template
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid')]
    private UuidInterface $uuid;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name: 'file_uuid', referencedColumnName: 'uuid', nullable: false)]
    private ?File $File = null;

    #[ORM\Column(enumType: TemplateEnum::class)]
    private ?TemplateEnum $type = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $number = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\OneToOne(mappedBy: 'template', cascade: ['persist', 'remove'])]
    private ?TemplateKey $key = null;

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function setUuid(UuidInterface $uuid): static
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getFile(): ?File
    {
        return $this->File;
    }

    public function setFile(File $File): static
    {
        $this->File = $File;

        return $this;
    }

    public function getType(): ?TemplateEnum
    {
        return $this->type;
    }

    public function setType(TemplateEnum $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): static
    {
        $this->number = $number;

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

    public function getKey(): ?TemplateKey
    {
        return $this->key;
    }

    public function setKey(TemplateKey $key): static
    {
        // set the owning side of the relation if necessary
        if ($key->getTemplate() !== $this) {
            $key->setTemplate($this);
        }

        $this->key = $key;

        return $this;
    }
}
