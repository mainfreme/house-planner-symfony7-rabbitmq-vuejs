<?php

namespace App\DataFixtures;

use App\Client\Domain\Entity\Client;
use App\Client\Domain\Entity\ClientAddress;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class ClientFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Dane testowe dla klientów
        $clientsData = [
            [
                'name' => 'Firma ABC Sp. z o.o.',
                'nip' => '1234567890',
                'regon' => '012345678',
                'email' => 'kontakt@abc.com',
                'phoneNumber' => '123456789',
                'country' => 'Polska',
                'phonePrefix' => '+48',
                'isCompany' => true,
                'isDelete' => false,
                'address' => [
                    'street' => 'Marszałkowska',
                    'postal_code' => '00-001',
                    'city' => 'Warszawa',
                    'state_province' => 'mazowieckie',
                    'country' => 'Polska',
                    'additional_info' => 'Budynek biurowy, III piętro',
                    'house_number' => '10',
                    'apartment_number' => '15',
                    'is_primary' => true,
                ]
            ],
            [
                'name' => 'Jan Kowalski',
                'nip' => null,
                'pesel' => '85010112345',
                'email' => 'jan.kowalski@example.com',
                'phoneNumber' => '987654321',
                'country' => 'Polska',
                'phonePrefix' => '+48',
                'isCompany' => false,
                'isDelete' => false,
                'address' => [
                    'street' => 'Długa',
                    'postal_code' => '80-001',
                    'city' => 'Gdańsk',
                    'state_province' => 'pomorskie',
                    'country' => 'Polska',
                    'additional_info' => 'Kamienica przy rynku',
                    'house_number' => '25',
                    'apartment_number' => '3',
                    'is_primary' => true,
                ]
            ],
            [
                'name' => 'Tech Solutions Ltd.',
                'nip' => '9876543210',
                'regon' => '987654321',
                'email' => 'info@techsolutions.com',
                'phoneNumber' => '555123456',
                'country' => 'Polska',
                'phonePrefix' => '+48',
                'isCompany' => true,
                'isDelete' => false,
                'address' => [
                    'street' => 'Świętokrzyska',
                    'postal_code' => '30-001',
                    'city' => 'Kraków',
                    'state_province' => 'małopolskie',
                    'country' => 'Polska',
                    'additional_info' => 'Centrum biznesowe, pokój 201',
                    'house_number' => '45',
                    'apartment_number' => '201',
                    'is_primary' => true,
                ]
            ],
            [
                'name' => 'Anna Nowak',
                'nip' => null,
                'pesel' => '92050567890',
                'email' => 'anna.nowak@example.com',
                'phoneNumber' => '666777888',
                'country' => 'Polska',
                'phonePrefix' => '+48',
                'isCompany' => false,
                'isDelete' => false,
                'address' => [
                    'street' => 'Poznańska',
                    'postal_code' => '60-001',
                    'city' => 'Poznań',
                    'state_province' => 'wielkopolskie',
                    'country' => 'Polska',
                    'additional_info' => 'Blok mieszkalny',
                    'house_number' => '7',
                    'apartment_number' => '12',
                    'is_primary' => true,
                ]
            ],
            [
                'name' => 'Global Services GmbH',
                'nip' => '1122334455',
                'regon' => '112233445',
                'email' => 'contact@globalservices.de',
                'phoneNumber' => '491234567',
                'country' => 'Niemcy',
                'phonePrefix' => '+49',
                'isCompany' => true,
                'isDelete' => false,
                'address' => [
                    'street' => 'Hauptstraße',
                    'postal_code' => '10115',
                    'city' => 'Berlin',
                    'state_province' => 'Berlin',
                    'country' => 'Niemcy',
                    'additional_info' => 'Biuro główne',
                    'house_number' => '123',
                    'apartment_number' => null,
                    'is_primary' => true,
                ]
            ]
        ];

        foreach ($clientsData as $clientData) {
            // Tworzymy adres najpierw
            $address = new ClientAddress();
            $address->setUuid(Uuid::uuid4());
            $address->setStreet($clientData['address']['street']);
            $address->setPostalCode($clientData['address']['postal_code']);
            $address->setCity($clientData['address']['city']);
            $address->setStateProvince($clientData['address']['state_province']);
            $address->setCountry($clientData['address']['country']);
            $address->setAdditionalInfo($clientData['address']['additional_info']);
            $address->setHouseNumber($clientData['address']['house_number']);
            $address->setApartmentNumber($clientData['address']['apartment_number']);
            $address->setIsPrimary($clientData['address']['is_primary']);
            $address->setAddedAt(new \DateTimeImmutable());

            // Tworzymy klienta
            $client = new Client(
                name: $clientData['name'],
                nip: $clientData['nip'],
                regon: $clientData['regon'] ?? null,
                pesel: $clientData['pesel'] ?? null,
                email: $clientData['email'],
                phoneNumber: $clientData['phoneNumber'],
                country: $clientData['country'],
                phonePrefix: $clientData['phonePrefix'],
                isCompany: $clientData['isCompany'],
                isDelete: $clientData['isDelete']
            );
            $client->setUuid(Uuid::uuid4());

            // Łączymy klienta z adresem
            $client->setAddress($address);
            $address->setClient($client);

            $manager->persist($address);
            $manager->persist($client);
        }

        $manager->flush();
    }
}
