<?php

declare(strict_types=1);

namespace App\Client\Domain\Entity;

use App\Client\Infrastructure\Persistence\Doctrine\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Name = null;

    #[ORM\Column(length: 255)]
    private ?string $nip = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $regon = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $pesel = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(nullable: true)]
    private ?int $number_phone = null;

    #[ORM\Column(length: 255)]
    private ?string $country = null;

    #[ORM\Column(length: 5)]
    private ?string $phone_prefix = null;

    /**
     * @var Collection<int, ClientAddress>
     */
    #[ORM\OneToMany(targetEntity: ClientAddress::class, mappedBy: 'client')]
    private Collection $address;

    public function __construct()
    {
        $this->address = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): static
    {
        $this->Name = $Name;

        return $this;
    }

    public function getNip(): ?string
    {
        return $this->nip;
    }

    public function setNip(string $nip): static
    {
        $this->nip = $nip;

        return $this;
    }

    public function getRegon(): ?string
    {
        return $this->regon;
    }

    public function setRegon(?string $regon): static
    {
        $this->regon = $regon;

        return $this;
    }

    public function getPesel(): ?string
    {
        return $this->pesel;
    }

    public function setPesel(?string $pesel): static
    {
        $this->pesel = $pesel;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getNumberPhone(): ?int
    {
        return $this->number_phone;
    }

    public function setNumberPhone(?int $number_phone): static
    {
        $this->number_phone = $number_phone;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getPhonePrefix(): ?string
    {
        return $this->phone_prefix;
    }

    public function setPhonePrefix(string $phone_prefix): static
    {
        $this->phone_prefix = $phone_prefix;

        return $this;
    }

    /**
     * @return Collection<int, ClientAddress>
     */
    public function getAddress(): Collection
    {
        return $this->address;
    }

    public function addAddress(ClientAddress $address): static
    {
        if (!$this->address->contains($address)) {
            $this->address->add($address);
            $address->setClient($this);
        }

        return $this;
    }

    public function removeAddress(ClientAddress $address): static
    {
        if ($this->address->removeElement($address)) {
            // set the owning side to null (unless already changed)
            if ($address->getClient() === $this) {
                $address->setClient(null);
            }
        }

        return $this;
    }
}
