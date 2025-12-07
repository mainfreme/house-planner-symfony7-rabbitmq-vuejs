<?php

declare(strict_types=1);

namespace App\Infrastructure\Fakturownia\Mapper;

//use App\Domain\Client\Entity\Client;
//use App\Domain\Client\Entity\ClientAddress;
//use App\Domain\Client\Entity\Contact;
use App\Client\Application\Dto\ClientAddressDto;
use App\Client\Application\Dto\ClientContactDto;
use App\Client\Domain\Entity\Client;
use App\Client\Domain\Entity\ClientAddress;
use App\Client\Domain\Entity\Contact;
use App\Shared\Domain\ValueObject\Nip;
use App\Shared\Domain\ValueObject\Pesel;

class FakturowniaClientMapper
{
    public function mapToDomainClient(array $fakturowniaData): Client
    {
        // 1. Mapowanie Client
        $client = new Client(
            name: $fakturowniaData['name'],
            nip: isset($fakturowniaData['tax_no']) ? (string)new Nip($fakturowniaData['tax_no']) : 'BRAK',
            country: $fakturowniaData['country'] ?? 'Polska',
            phonePrefix: '+48', // Domyślny prefix dla Polski
            regon: null, // Brak bezpośredniego odpowiednika w popularnych polach klienta Fakturowni
            pesel: null, // Brak bezpośredniego odpowiednika
            email: $fakturowniaData['email'] ?? null,
            phoneNumber: $fakturowniaData['phone'] ?? null,
            isCompany: ($fakturowniaData['kind'] ?? 'company') === 'company', // 'company' vs 'person'
            isDelete: $fakturowniaData['is_archived'] ?? false // Mapowanie is_archived z Fakturowni na is_delete
        );

        // 2. Mapowanie ClientAddress (zakładamy, że jest 1 adres główny)
        $address = new ClientAddressDto(
        // id (autoincrement, nie mapujemy)
        // client_id (integer, ustawione po zapisie)
        // street (varchar(255))
            $fakturowniaData['street'] ?? null,
            // postal_code (varchar(20))
            $fakturowniaData['zip_code'] ?? null,
            // city (varchar(100))
            $fakturowniaData['city'] ?? null,
            // state_province (varchar(100))
            null, // Brak bezpośredniego odpowiednika
            // country (varchar(100))
            $fakturowniaData['country'] ?? null,
            // additional_info (text)
            null, // Brak bezpośredniego odpowiednika
            // house_number (varchar(10))
            null, // Fakturownia łączy street + house_number, trudne do rozdzielenia bez heurystyki.
            // apartment_number (varchar(15))
            null, // Brak bezpośredniego odpowiednika
            // is_primary (boolean)
            true,
            // added_at (datetime)
            new \DateTimeImmutable()
        );

        $client->setAddress($address);

        // 3. Mapowanie Contact (na podstawie głównych danych klienta, jeśli kontakt ma swoje osobne pola w Fakturowni, trzeba by to dostosować)
        // W standardowej odpowiedzi clients.json, pola 'contact' nie są osobne. Mapujemy główne dane jako jeden kontakt.
        $contact = new ClientContactDto(
        // id (autoincrement, nie mapujemy)
        // name (varchar(255))
            $fakturowniaData['person'] ?? $fakturowniaData['name'], // Jeśli jest "Osoba kontaktowa", użyj jej.
            // surname (varchar(255))
            null, // Brak bezpośredniego odpowiednika
            // email (varchar(100))
            $fakturowniaData['email'] ?? null,
            // phone_number (varchar(100))
            $fakturowniaData['phone'] ?? null,
            // country (varchar(100))
            $fakturowniaData['country'] ?? null,
            // language (varchar(10))
            $fakturowniaData['default_language'] ?? null,
            // area_code (varchar(10))
            null, // Brak bezpośredniego odpowiednika
            // note (text)
            $fakturowniaData['description'] ?? null,
            // added_at (datetime)
            new \DateTimeImmutable()
        );

        $client->addContact($contact);

        return $client;
    }

    /**
     * @param array $fakturowniaDataArray Tablica klientów z Fakturowni.
     * @return Client[]
     */
    public function mapArrayToDomainClients(array $fakturowniaDataArray): array
    {
        $clients = [];
        foreach ($fakturowniaDataArray as $fakturowniaData) {
            $clients[] = $this->mapToDomainClient($fakturowniaData);
        }
        return $clients;
    }
}
