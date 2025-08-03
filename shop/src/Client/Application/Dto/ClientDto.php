<?php

declare(strict_types=1);

namespace App\Client\Application\Dto;

use App\Application\Shared\Dto\ArrayMappableDtoInterface;
use App\Application\Shared\Dto\ResponseDtoInterface;
use App\Client\Domain\Entity\Client;

class ClientDto implements ResponseDtoInterface, ArrayMappableDtoInterface
{

    public function __construct(
        public int     $id,
        public string  $name,
        public ?string $nip = null,
        public ?string $regon = null,
        public ?string $pesel = null,
        public ?string $email = null,
        public ?int    $number_phone = null,
        public ?string $country = null,
        public ?string $phone_prefix = null,
    )
    {
    }

    public function getArray(): array
    {
        return get_object_vars($this);
    }

    public static function fromEntity(Client $client): self
    {
        return new self(
            id: $client->getId(),
            name: $client->getName(),
            nip: $client->getNip(),
            regon: $client->getRegon(),
            pesel: $client->getPesel(),
            email: $client->getEmail(),
            number_phone: $client->getNumberPhone(),
            country: $client->getCountry(),
            phone_prefix: $client->getPhonePrefix(),
        );
    }

    public static function fromArray(array $array): self
    {
        return new self(
            id: $array['id'],
            name: $array['name'],
        );
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return ClientDto
     */
    public function setId(int $id): ClientDto
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return ClientDto
     */
    public function setName(string $name): ClientDto
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getNip(): ?string
    {
        return $this->nip;
    }

    /**
     * @param string|null $nip
     * @return ClientDto
     */
    public function setNip(?string $nip): ClientDto
    {
        $this->nip = $nip;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getRegon(): ?string
    {
        return $this->regon;
    }

    /**
     * @param string|null $regon
     * @return ClientDto
     */
    public function setRegon(?string $regon): ClientDto
    {
        $this->regon = $regon;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPesel(): ?string
    {
        return $this->pesel;
    }

    /**
     * @param string|null $pesel
     * @return ClientDto
     */
    public function setPesel(?string $pesel): ClientDto
    {
        $this->pesel = $pesel;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     * @return ClientDto
     */
    public function setEmail(?string $email): ClientDto
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getNumberPhone(): ?int
    {
        return $this->number_phone;
    }

    /**
     * @param int|null $number_phone
     * @return ClientDto
     */
    public function setNumberPhone(?int $number_phone): ClientDto
    {
        $this->number_phone = $number_phone;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * @param string|null $country
     * @return ClientDto
     */
    public function setCountry(?string $country): ClientDto
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhonePrefix(): ?string
    {
        return $this->phone_prefix;
    }

    /**
     * @param string|null $phone_prefix
     * @return ClientDto
     */
    public function setPhonePrefix(?string $phone_prefix): ClientDto
    {
        $this->phone_prefix = $phone_prefix;
        return $this;
    }


}
