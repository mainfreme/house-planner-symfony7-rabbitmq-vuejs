<?php

declare(strict_types=1);

namespace App\Tests\Client\Infrastructure\Company;

use App\Client\Infrastructure\Company\GUSApiClient;
use PHPUnit\Framework\TestCase;
use SoapClient;
use SoapFault;

class GUSApiClientTest extends TestCase
{
    private string $apiKey;

    protected function setUp(): void
    {
        $this->apiKey = 'test-api-key';
    }

    public function testFindCompanyByNipReturnsCompanyData(): void
    {
        // Arrange
        $nip = '1234567890';
        
        // Mock SoapClient response
        $mockResponse = (object)[
            'DaneSzukajPodmiotyResult' => (object)[
                'Dane' => (object)[
                    'Nazwa' => 'Testowa Firma Sp. z o.o.',
                    'Nip' => $nip,
                    'Regon' => '012345678',
                    'Ulica' => 'Testowa',
                    'NrNieruchomosci' => '10',
                    'NrLokalu' => '15',
                    'KodPocztowy' => '00-001',
                    'Miejscowosc' => 'Warszawa',
                ]
            ]
        ];

        $soapClient = $this->createMock(SoapClient::class);
        $soapClient->expects($this->once())
            ->method('Zaloguj')
            ->with(['pKluczUzytkownika' => $this->apiKey])
            ->willReturn('session-id-123');

        $soapClient->expects($this->once())
            ->method('DaneSzukajPodmioty')
            ->with(['pNip' => $nip])
            ->willReturn($mockResponse);

        // Użyjemy reflection aby wstrzyknąć mock SoapClient
        $client = new GUSApiClient($this->apiKey);
        $reflection = new \ReflectionClass($client);
        $property = $reflection->getProperty('client');
        $property->setAccessible(true);
        $property->setValue($client, $soapClient);

        // Act
        $result = $client->findCompanyByNip($nip);

        // Assert
        $this->assertIsArray($result);
        $this->assertEquals('Testowa Firma Sp. z o.o.', $result['Nazwa']);
        $this->assertEquals($nip, $result['Nip']);
        $this->assertEquals('012345678', $result['Regon']);
    }

    public function testFindCompanyByNipReturnsNullWhenCompanyNotFound(): void
    {
        // Arrange
        $nip = '9999999999';
        
        $mockResponse = (object)[
            'DaneSzukajPodmiotyResult' => null
        ];

        $soapClient = $this->createMock(SoapClient::class);
        $soapClient->expects($this->once())
            ->method('Zaloguj')
            ->willReturn('session-id-123');

        $soapClient->expects($this->once())
            ->method('DaneSzukajPodmioty')
            ->with(['pNip' => $nip])
            ->willReturn($mockResponse);

        $client = new GUSApiClient($this->apiKey);
        $reflection = new \ReflectionClass($client);
        $property = $reflection->getProperty('client');
        $property->setAccessible(true);
        $property->setValue($client, $soapClient);

        // Act
        $result = $client->findCompanyByNip($nip);

        // Assert
        $this->assertNull($result);
    }

    public function testFindCompanyByNipHandlesArrayResponse(): void
    {
        // Arrange
        $nip = '1234567890';
        
        $mockResponse = (object)[
            'DaneSzukajPodmiotyResult' => (object)[
                'Dane' => [
                    (object)[
                        'Nazwa' => 'Testowa Firma Sp. z o.o.',
                        'Nip' => $nip,
                    ]
                ]
            ]
        ];

        $soapClient = $this->createMock(SoapClient::class);
        $soapClient->expects($this->once())
            ->method('Zaloguj')
            ->willReturn('session-id-123');

        $soapClient->expects($this->once())
            ->method('DaneSzukajPodmioty')
            ->willReturn($mockResponse);

        $client = new GUSApiClient($this->apiKey);
        $reflection = new \ReflectionClass($client);
        $property = $reflection->getProperty('client');
        $property->setAccessible(true);
        $property->setValue($client, $soapClient);

        // Act
        $result = $client->findCompanyByNip($nip);

        // Assert
        $this->assertIsArray($result);
        $this->assertEquals('Testowa Firma Sp. z o.o.', $result['Nazwa']);
    }

    public function testFindCompanyByNipThrowsExceptionOnSoapFault(): void
    {
        // Arrange
        $nip = '1234567890';
        
        $soapClient = $this->createMock(SoapClient::class);
        $soapClient->expects($this->once())
            ->method('Zaloguj')
            ->willReturn('session-id-123');

        $soapFault = new SoapFault('Server', 'Błąd serwera GUS');
        $soapClient->expects($this->once())
            ->method('DaneSzukajPodmioty')
            ->willThrowException($soapFault);

        $client = new GUSApiClient($this->apiKey);
        $reflection = new \ReflectionClass($client);
        $property = $reflection->getProperty('client');
        $property->setAccessible(true);
        $property->setValue($client, $soapClient);

        // Act & Assert
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Błąd połączenia z GUS');

        $client->findCompanyByNip($nip);
    }

    public function testFindCompanyByNipReturnsNullWhenDaneIsNull(): void
    {
        // Arrange
        $nip = '1234567890';
        
        $mockResponse = (object)[
            'DaneSzukajPodmiotyResult' => (object)[
                'Dane' => null
            ]
        ];

        $soapClient = $this->createMock(SoapClient::class);
        $soapClient->expects($this->once())
            ->method('Zaloguj')
            ->willReturn('session-id-123');

        $soapClient->expects($this->once())
            ->method('DaneSzukajPodmioty')
            ->willReturn($mockResponse);

        $client = new GUSApiClient($this->apiKey);
        $reflection = new \ReflectionClass($client);
        $property = $reflection->getProperty('client');
        $property->setAccessible(true);
        $property->setValue($client, $soapClient);

        // Act
        $result = $client->findCompanyByNip($nip);

        // Assert
        $this->assertNull($result);
    }
}

