<?php

require_once __DIR__ . '/shop/vendor/autoload.php';

use App\Client\Domain\Entity\Client;
use App\Client\Domain\Entity\ClientAddress;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Doctrine\DBAL\DriverManager;
use Ramsey\Uuid\Uuid;

// Konfiguracja Doctrine
$paths = [__DIR__ . '/shop/src'];
$isDevMode = true;

// Konfiguracja bazy danych - ustawienia z docker-compose.yml
$dbParams = [
    'driver'   => 'pdo_pgsql',
    'host'     => 'db', // nazwa kontenera w Docker
    'port'     => 5432,
    'dbname'   => 'symfony',
    'user'     => 'symfony',
    'password' => 'symfony',
];

try {
    $config = Setup::createAttributeMetadataConfiguration($paths, $isDevMode);
    $connection = DriverManager::getConnection($dbParams, $config);
    $entityManager = EntityManager::create($connection, $config);

    echo "Rozpoczynam napełnianie tabel Client i ClientAddress...\n";

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

    $clientsAdded = 0;
    $addressesAdded = 0;

    foreach ($clientsData as $index => $clientData) {
        echo "Tworzę klienta: {$clientData['name']}...\n";
        $clientsAdded++;
        $addressesAdded++;

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
            nip: $clientData['nip'] ?? 'BRAK',
            country: $clientData['country'],
            phonePrefix: $clientData['phonePrefix'],
            regon: $clientData['regon'] ?? null,
            pesel: $clientData['pesel'] ?? null,
            email: $clientData['email'],
            phoneNumber: $clientData['phoneNumber'],
            isCompany: $clientData['isCompany'],
            isDelete: isset($clientData['isDelete']) ? (bool)$clientData['isDelete'] : false
        );
        $client->setUuid(Uuid::uuid4());

        // Łączymy klienta z adresem
        $client->setAddress($address);
        $address->setClient($client);

        $entityManager->persist($address);
        $entityManager->persist($client);

        if ($index % 2 === 1) { // Co drugiego klienta flush, żeby nie przeciążać pamięci
            $entityManager->flush();
        }
    }

    $entityManager->flush();
    echo "\n✅ Pomyślnie dodano dane do bazy:\n";
    echo "   👥 Klienci: {$clientsAdded}\n";
    echo "   📍 Adresy: {$addressesAdded}\n";
    echo "   📊 Razem rekordów: " . ($clientsAdded + $addressesAdded) . "\n";

} catch (\Exception $e) {
    echo "❌ Błąd: " . $e->getMessage() . "\n";
    echo "Sprawdź konfigurację bazy danych w skrypcie.\n";
}
