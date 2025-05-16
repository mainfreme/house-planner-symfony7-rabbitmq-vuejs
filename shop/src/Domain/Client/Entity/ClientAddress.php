<?php

namespace App\Domain\Client\Entity;

use App\Infrastructure\Persistence\Doctrine\Client\ClientAddressRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientAddressRepository::class)]
class ClientAddress
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Street = null;

    #[ORM\Column(length: 20)]
    private ?string $postal_code = null;

    #[ORM\Column(length: 100)]
    private ?string $city = null;

    #[ORM\Column(length: 100)]
    private ?string $state_province = null;

    #[ORM\Column(length: 100)]
    private ?string $country = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $additional_info = null;

    #[ORM\Column(length: 10)]
    private ?string $house_number = null;

    #[ORM\Column(length: 15)]
    private ?string $apartment_number = null;

    #[ORM\Column]
    private ?bool $is_primary = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $added_at = null;

    #[ORM\ManyToOne(inversedBy: 'address')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Client $client = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->Street;
    }

    public function setStreet(string $Street): static
    {
        $this->Street = $Street;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postal_code;
    }

    public function setPostalCode(string $postal_code): static
    {
        $this->postal_code = $postal_code;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getStateProvince(): ?string
    {
        return $this->state_province;
    }

    public function setStateProvince(string $state_province): static
    {
        $this->state_province = $state_province;

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

    public function getAdditionalInfo(): ?string
    {
        return $this->additional_info;
    }

    public function setAdditionalInfo(string $additional_info): static
    {
        $this->additional_info = $additional_info;

        return $this;
    }

    public function getHouseNumber(): ?string
    {
        return $this->house_number;
    }

    public function setHouseNumber(string $house_number): static
    {
        $this->house_number = $house_number;

        return $this;
    }

    public function getApartmentNumber(): ?string
    {
        return $this->apartment_number;
    }

    public function setApartmentNumber(string $apartment_number): static
    {
        $this->apartment_number = $apartment_number;

        return $this;
    }

    public function isPrimary(): ?bool
    {
        return $this->is_primary;
    }

    public function setIsPrimary(bool $is_primary): static
    {
        $this->is_primary = $is_primary;

        return $this;
    }

    public function getAddedAt(): ?\DateTimeImmutable
    {
        return $this->added_at;
    }

    public function setAddedAt(\DateTimeImmutable $added_at): static
    {
        $this->added_at = $added_at;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        $this->client = $client;

        return $this;
    }
}
