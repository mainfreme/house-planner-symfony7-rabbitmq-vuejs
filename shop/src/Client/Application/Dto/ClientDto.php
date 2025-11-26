<?php

declare(strict_types=1);

namespace App\Client\Application\Dto;

use App\Client\Domain\Entity\Client;
use App\Shared\Application\Dto\ArrayMappableInterface;
use App\Shared\Application\Dto\ResponseDtoInterface;

class ClientDto implements ResponseDtoInterface, ArrayMappableInterface
{

    public function __construct(
        public ?int    $id,
        public ?string $name,
        public ?string $nip = null,
        public ?string $regon = null,
        public ?string $pesel = null,
        public ?string $email = null,
        public ?string $country = null,
        public ?string $phonePrefix = null,
        public ?string $phoneNumber = null,
        public bool    $isCompany = false,
    )
    {
    }

    public function getArray(): array
    {
        return get_object_vars($this);
    }

    public function toApiArray(): array
    {
        return [
            'data' => $this->getArray(),
        ];
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
            country: $client->getCountry(),
            phonePrefix: (string)$client->getPhonePrefix(),
            phoneNumber: (string)$client->getPhoneNumber(),
            isCompany: $client->getIsCompany(),
        );
    }

    public static function fromArray(array $array): self
    {
        return new self(
            id: (int)$array['id'],
            name: (string)$array['name'],
            nip: $array['nip'] ?? null,
            regon: $array['regon'] ?? null,
            pesel: $array['pesel'] ?? null,
            email: $array['email'] ?? null,
            country: $array['country'] ?? null,
            phonePrefix: (string)$array['phonePrefix'] ?? null,
            phoneNumber: (string)$array['phoneNumber'] ?? null,
            isCompany: $array['isCompany'] ?? false,
        );
    }

    public function setPhoneNumber(?string $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber !== null
            ? preg_replace('/\s+/', '', (string)$phoneNumber)
            : null;
    }
}
