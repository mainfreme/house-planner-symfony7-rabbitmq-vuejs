<?php

declare(strict_types=1);

namespace App\Client\Application\Dto;

use App\Client\Domain\Entity\Client;
use App\Client\Domain\Entity\ClientAddress;
use App\Shared\Application\Dto\ArrayMappableInterface;

class ClientAddressDto implements ArrayMappableInterface
{
    public function __construct(
        public readonly ?int                $id,
        public readonly ?string             $street,
        public readonly ?string             $postal_code,
        public readonly ?string             $city,
        public readonly ?string             $state_province,
        public readonly ?string             $country,
        public readonly ?string             $additional_info,
        public readonly ?string             $house_number,
        public readonly ?string             $apartment_number,
        public readonly ?bool               $is_primary,
        public readonly ?string             $added_at,
        public          ?Client             $client,
    )
    {
    }

    public static function fromEntity(ClientAddress $entity): self
    {
        return new self(
            id: $entity->getId(),
            street: $entity->getStreet(),
            postal_code: $entity->getPostalCode(),
            city: $entity->getCity(),
            state_province: $entity->getStateProvince(),
            country: $entity->getCountry(),
            additional_info: $entity->getAdditionalInfo(),
            house_number: $entity->getHouseNumber(),
            apartment_number: $entity->getApartmentNumber(),
            is_primary: $entity->getIsPrimary(),
            added_at: $entity->getAddedAt()->format('Y-m-d'),
            client: $entity->getClient(),
        );
    }

    public static function fromArray(array $dto)
    {
        // TODO: Implement fromArray() method.
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

    public function setClient(Client $client): ClientAddressDto
    {
        $this->client = $client;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }
}
