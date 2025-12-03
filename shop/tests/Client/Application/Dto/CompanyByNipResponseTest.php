<?php

declare(strict_types=1);

namespace App\Tests\Client\Application\Dto;

use App\Client\Application\Dto\CompanyByNipResponse;
use PHPUnit\Framework\TestCase;

class CompanyByNipResponseTest extends TestCase
{
    public function testEmptyResponse(): void
    {
        // Arrange & Act
        $response = new CompanyByNipResponse();

        // Assert
        $this->assertNull($response->name);
        $this->assertNull($response->nip);
        $this->assertNull($response->regon);
        $this->assertNull($response->street);
        $this->assertNull($response->houseNumber);
        $this->assertNull($response->apartmentNumber);
        $this->assertNull($response->postalCode);
        $this->assertNull($response->city);
        $this->assertNull($response->country);
    }

    public function testResponseWithGusData(): void
    {
        // Arrange
        $gusData = [
            'Nazwa' => 'Testowa Firma Sp. z o.o.',
            'Nip' => '1234567890',
            'Regon' => '012345678',
            'Ulica' => 'Testowa',
            'NrNieruchomosci' => '10',
            'NrLokalu' => '15',
            'KodPocztowy' => '00-001',
            'Miejscowosc' => 'Warszawa',
        ];

        // Act
        $response = new CompanyByNipResponse($gusData);

        // Assert
        $this->assertEquals('Testowa Firma Sp. z o.o.', $response->name);
        $this->assertEquals('1234567890', $response->nip);
        $this->assertEquals('012345678', $response->regon);
        $this->assertEquals('Testowa', $response->street);
        $this->assertEquals('10', $response->houseNumber);
        $this->assertEquals('15', $response->apartmentNumber);
        $this->assertEquals('00-001', $response->postalCode);
        $this->assertEquals('Warszawa', $response->city);
        $this->assertEquals('Polska', $response->country);
    }

    public function testResponseWithLowercaseGusData(): void
    {
        // Arrange
        $gusData = [
            'nazwa' => 'Testowa Firma Sp. z o.o.',
            'nip' => '1234567890',
            'regon' => '012345678',
            'ulica' => 'Testowa',
            'nrNieruchomosci' => '10',
            'nrLokalu' => '15',
            'kodPocztowy' => '00-001',
            'miejscowosc' => 'Warszawa',
        ];

        // Act
        $response = new CompanyByNipResponse($gusData);

        // Assert
        $this->assertEquals('Testowa Firma Sp. z o.o.', $response->name);
        $this->assertEquals('1234567890', $response->nip);
        $this->assertEquals('012345678', $response->regon);
        $this->assertEquals('Testowa', $response->street);
        $this->assertEquals('10', $response->houseNumber);
        $this->assertEquals('15', $response->apartmentNumber);
        $this->assertEquals('00-001', $response->postalCode);
        $this->assertEquals('Warszawa', $response->city);
    }

    public function testResponseWithObjectData(): void
    {
        // Arrange
        $gusData = (object)[
            'Nazwa' => 'Testowa Firma Sp. z o.o.',
            'Nip' => '1234567890',
            'Regon' => '012345678',
        ];

        // Act
        $response = new CompanyByNipResponse($gusData);

        // Assert
        $this->assertEquals('Testowa Firma Sp. z o.o.', $response->name);
        $this->assertEquals('1234567890', $response->nip);
        $this->assertEquals('012345678', $response->regon);
    }

    public function testToArray(): void
    {
        // Arrange
        $gusData = [
            'Nazwa' => 'Testowa Firma Sp. z o.o.',
            'Nip' => '1234567890',
            'Regon' => '012345678',
            'Ulica' => 'Testowa',
            'NrNieruchomosci' => '10',
            'NrLokalu' => '15',
            'KodPocztowy' => '00-001',
            'Miejscowosc' => 'Warszawa',
        ];
        $response = new CompanyByNipResponse($gusData);

        // Act
        $array = $response->toArray();

        // Assert
        $this->assertIsArray($array);
        $this->assertEquals('Testowa Firma Sp. z o.o.', $array['name']);
        $this->assertEquals('1234567890', $array['nip']);
        $this->assertEquals('012345678', $array['regon']);
        $this->assertEquals('Testowa', $array['street']);
        $this->assertEquals('10', $array['houseNumber']);
        $this->assertEquals('15', $array['apartmentNumber']);
        $this->assertEquals('00-001', $array['postalCode']);
        $this->assertEquals('Warszawa', $array['city']);
        $this->assertEquals('Polska', $array['country']);
        $this->assertArrayHasKey('email', $array);
        $this->assertArrayHasKey('phone', $array);
    }

    public function testPartialData(): void
    {
        // Arrange
        $gusData = [
            'Nazwa' => 'Testowa Firma',
            'Nip' => '1234567890',
        ];

        // Act
        $response = new CompanyByNipResponse($gusData);

        // Assert
        $this->assertEquals('Testowa Firma', $response->name);
        $this->assertEquals('1234567890', $response->nip);
        $this->assertNull($response->regon);
        $this->assertNull($response->street);
        $this->assertEquals('Polska', $response->country); // Zawsze ustawione
    }
}

