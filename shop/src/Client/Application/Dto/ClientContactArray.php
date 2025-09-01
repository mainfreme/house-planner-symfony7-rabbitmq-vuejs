<?php

declare(strict_types=1);

namespace App\Client\Application\Dto;


use App\Client\Domain\Entity\Contact;
use App\Shared\Application\Dto\ArrayMappableInterface;
use App\Shared\Application\Dto\ResponseDtoInterface;

class ClientContactArray implements ResponseDtoInterface, ArrayMappableInterface
{
    public function __construct(
        public readonly ?int                $id,
        public readonly ?string             $name,
        public readonly ?string             $surname,
        public readonly ?string             $email,
        public readonly ?string             $phoneNumber,
        public readonly string              $country,
        public readonly string              $language,
        public readonly ?string             $areaCode,
        public readonly string              $note,
        public readonly ?\DateTimeImmutable $added_at,
    )
    {
    }

    public static function fromEntity(Contact $contact): self
    {
        return new self(
            id: $contact->getId(),
            name: $contact->getName(),
            surname: $contact->getSurname(),
            email: $contact->getEmail(),
            phoneNumber: $contact->getPhoneNumber(),
            country: $contact->getCountry(),
            language: $contact->getLanguage(),
            areaCode: $contact->getAreaCode(),
            note: $contact->getNote(),
            added_at: $contact->getAddedAt(),
        );
    }

    public static function fromArray(array $dto): self
    {

    }

    public function getArray(): array
    {
        return get_object_vars($this);
    }


    public function toApiArray(): array
    {
        return [
            'data'=> $this->getArray(),
        ];
    }


}
