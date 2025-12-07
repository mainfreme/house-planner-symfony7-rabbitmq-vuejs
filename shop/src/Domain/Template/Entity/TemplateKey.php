<?php

namespace App\Domain\Template\Entity;

use App\Repository\TemplateKeyRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity(repositoryClass: TemplateKeyRepository::class)]
class TemplateKey
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid')]
    private UuidInterface $uuid;

    #[ORM\OneToOne(inversedBy: 'key', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name: 'template_uuid', referencedColumnName: 'uuid', nullable: false)]
    private ?Template $template = null;

    #[ORM\Column(length: 255)]
    private ?string $key = null;

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function setUuid(UuidInterface $uuid): static
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getTemplate(): ?Template
    {
        return $this->template;
    }

    public function setTemplate(Template $template): static
    {
        $this->template = $template;

        return $this;
    }

    public function getKey(): ?string
    {
        return $this->key;
    }

    public function setKey(string $key): static
    {
        $this->key = $key;

        return $this;
    }
}
