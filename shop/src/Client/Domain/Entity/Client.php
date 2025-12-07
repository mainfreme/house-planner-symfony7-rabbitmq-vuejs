<?php

declare(strict_types=1);

namespace App\Client\Domain\Entity;

use App\Client\Domain\Entity\ClientAddress;
use App\Client\Infrastructure\Persistence\Doctrine\ClientRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid')]
    private UuidInterface $uuid;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private string $nip;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $regon = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $pesel = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(name: 'number_phone', length: 15, nullable: true)]
    private ?string $phoneNumber = null;

    #[ORM\Column(length: 255)]
    private string $country;

    #[ORM\Column(length: 5)]
    private string $phonePrefix;

    #[ORM\Column(name: 'is_delete', type: 'boolean', options: ['default' => false])]
    private bool $isDelete = false;

    #[ORM\Column(name: 'is_company', type: 'boolean', options: ['default' => false])]
    private bool $isCompany = false;

    #[ORM\OneToOne(targetEntity: ClientAddress::class)]
    #[ORM\JoinColumn(name: 'address_uuid', referencedColumnName: 'uuid')]
    private ?ClientAddress $address = null;

    public function __construct(
        string $name,
        string $nip,
        string $country,
        string $phonePrefix,
        ?string $regon = null,
        ?string $pesel = null,
        ?string $email = null,
        ?string $phoneNumber = null,
        bool $isCompany = false,
        bool $isDelete = false
    ) {
        $this->name = $name;
        $this->nip = $nip;
        $this->regon = $regon;
        $this->pesel = $pesel;
        $this->email = $email;
        $this->phoneNumber = $phoneNumber;
        $this->country = $country;
        $this->phonePrefix = $phonePrefix;
        $this->isCompany = $isCompany;
        $this->isDelete = $isDelete;
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function setUuid(UuidInterface $uuid): static
    {
        $this->uuid = $uuid;

        return $this;
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

    public function getNip(): string
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

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): static
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getPhonePrefix(): string
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

    public function getAddress(): ?ClientAddress
    {
        return $this->address;
    }

    public function setAddress(?ClientAddress $address): static
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Sprawdza czy klient ma przypisany adres
     */
    public function hasAddress(): bool
    {
        return $this->address !== null;
    }

    /**
     * Pobiera ulicę z powiązanego adresu
     */
    public function getAddressStreet(): ?string
    {
        return $this->address?->getStreet();
    }

    /**
     * Pobiera miasto z powiązanego adresu
     */
    public function getAddressCity(): ?string
    {
        return $this->address?->getCity();
    }

    /**
     * Pobiera kod pocztowy z powiązanego adresu
     */
    public function getAddressPostalCode(): ?string
    {
        return $this->address?->getPostalCode();
    }

    /**
     * Sprawdza czy klient jest osobą fizyczną (na podstawie obecności PESEL)
     */
    public function isIndividual(): bool
    {
        return !empty($this->pesel);
    }

    /**
     * Sprawdza czy klient jest firmą (na podstawie flagi isCompany)
     */
    public function isCompanyEntity(): bool
    {
        return $this->isCompany;
    }

    /**
     * Sprawdza czy klient jest oznaczony do usunięcia
     */
    public function isMarkedForDeletion(): bool
    {
        return $this->isDelete;
    }

    /**
     * Pobiera pełny adres jako string
     */
    public function getFullAddress(): ?string
    {
        if (!$this->address) {
            return null;
        }

        $parts = array_filter([
            $this->address->getStreet(),
            $this->address->getHouseNumber(),
            $this->address->getApartmentNumber() ? '/' . $this->address->getApartmentNumber() : null,
            $this->address->getPostalCode(),
            $this->address->getCity(),
            $this->address->getCountry()
        ]);

        return implode(', ', $parts);
    }

    /**
     * Pobiera informacje kontaktowe jako array
     */
    public function getContactInfo(): array
    {
        return [
            'email' => $this->email,
            'phone' => $this->phoneNumber ? $this->phonePrefix . ' ' . $this->phoneNumber : null,
            'country' => $this->country
        ];
    }

    public function getIsDelete(): bool
    {
        return $this->isDelete;
    }

    public function setIsDelete(bool $isDelete): static
    {
        $this->isDelete = $isDelete;
        return $this;
    }

    public function getIsCompany(): bool
    {
        return $this->isCompany;
    }

    public function setIsCompany(bool $isCompany): static
    {
        $this->isCompany = $isCompany;
        return $this;
    }


}
