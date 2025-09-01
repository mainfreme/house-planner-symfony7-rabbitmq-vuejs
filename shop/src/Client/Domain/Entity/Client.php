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
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $nip = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $regon = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $pesel = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(nullable: true)]
    private ?int $phoneNumber = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $country = null;

    #[ORM\Column(length: 5, nullable: true)]
    private ?string $phonePrefix = null;

    #[ORM\Column(type: 'boolean', nullable: true, options: ['default' => false])]
    private ?bool $isDelete = null;

    #[ORM\Column(type: 'boolean', nullable: true, options: ['default' => false])]
    private bool $isCompany = false;

//    /**
//     * @var Collection<int, ClientAddress>
//     */
//    #[ORM\OneToMany(targetEntity: ClientAddress::class, mappedBy: 'client')]
//    private Collection $address;

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
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

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

    public function getPhoneNumber(): ?int
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?int $phoneNumber): static
    {
        $this->phoneNumber = $phoneNumber;

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
        return $this->phonePrefix;
    }

    public function setPhonePrefix(string $phonePrefix): static
    {
        $this->phonePrefix = $phonePrefix;

        return $this;
    }

//    /**
//     * @return Collection<int, ClientAddress>
//     */
//    public function getAddress(): Collection
//    {
//        return $this->address;
//    }
//
//    public function addAddress(ClientAddress $address): static
//    {
//        if (!$this->address->contains($address)) {
//            $this->address->add($address);
//            $address->setClient($this);
//        }
//
//        return $this;
//    }

//    public function removeAddress(ClientAddress $address): static
//    {
//        if ($this->address->removeElement($address)) {
//            // set the owning side to null (unless already changed)
//            if ($address->getClient() === $this) {
//                $address->setClient(null);
//            }
//        }
//
//        return $this;
//    }

    /**
     * @return bool|null
     */
    public function getIsDelete(): ?bool
    {
        return $this->isDelete;
    }

    /**
     * @param bool|null $isDelete
     * @return static
     */
    public function setIsDelete(?bool $isDelete): static
    {
        $this->isDelete = $isDelete;
        return $this;
    }

    public function getIsCompany(): bool
    {
        return $this->isCompany;
    }

    public function setIsCompany(bool $isCompany): Client
    {
        $this->isCompany = $isCompany;
        return $this;
    }


}
