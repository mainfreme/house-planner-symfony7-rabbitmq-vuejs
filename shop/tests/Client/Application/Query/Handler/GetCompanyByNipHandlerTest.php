<?php

declare(strict_types=1);

namespace App\Tests\Client\Application\Query\Handler;

use App\Client\Application\Dto\CompanyByNipResponse;
use App\Client\Application\Query\GetCompanyByNipQuery;
use App\Client\Application\Query\Handler\GetCompanyByNipHandler;
use App\Client\Infrastructure\Company\GUSApiClient;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class GetCompanyByNipHandlerTest extends TestCase
{
    private LoggerInterface $logger;
    private GUSApiClient $gusApiClient;

    protected function setUp(): void
    {
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->gusApiClient = $this->createMock(GUSApiClient::class);
    }

    public function testHandleReturnsCompanyData(): void
    {
        // Arrange
        $nip = '1234567890';
        $query = new GetCompanyByNipQuery($nip);

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

        // Mock GUSApiClient
        $this->gusApiClient->expects($this->once())
            ->method('findCompanyByNip')
            ->with($nip)
            ->willReturn($gusData);

        // Mock logger
        $this->logger->expects($this->once())
            ->method('info')
            ->with('Pobieranie danych firmy z GUS', ['nip' => $nip]);

        $this->logger->expects($this->once())
            ->method('info')
            ->with('Pomyślnie pobrano dane firmy z GUS', ['nip' => $nip]);

        $handler = new GetCompanyByNipHandler($this->gusApiClient, $this->logger);

        // Act
        $response = $handler->handle($query);

        // Assert
        $this->assertInstanceOf(CompanyByNipResponse::class, $response);
        $this->assertEquals('Testowa Firma Sp. z o.o.', $response->name);
        $this->assertEquals('1234567890', $response->nip);
        $this->assertEquals('012345678', $response->regon);
        $this->assertEquals('Testowa', $response->street);
        $this->assertEquals('10', $response->houseNumber);
        $this->assertEquals('15', $response->apartmentNumber);
        $this->assertEquals('00-001', $response->postalCode);
        $this->assertEquals('Warszawa', $response->city);
    }

    public function testHandleReturnsEmptyResponseWhenCompanyNotFound(): void
    {
        // Arrange
        $nip = '9999999999';
        $query = new GetCompanyByNipQuery($nip);

        // Mock GUSApiClient - zwraca null gdy nie znaleziono
        $this->gusApiClient->expects($this->once())
            ->method('findCompanyByNip')
            ->with($nip)
            ->willReturn(null);

        // Mock logger
        $this->logger->expects($this->once())
            ->method('info')
            ->with('Pobieranie danych firmy z GUS', ['nip' => $nip]);

        $this->logger->expects($this->once())
            ->method('warning')
            ->with('Nie znaleziono firmy w GUS', ['nip' => $nip]);

        $handler = new GetCompanyByNipHandler($this->gusApiClient, $this->logger);

        // Act
        $response = $handler->handle($query);

        // Assert
        $this->assertInstanceOf(CompanyByNipResponse::class, $response);
        $this->assertNull($response->name);
        $this->assertNull($response->nip);
    }

    public function testHandleThrowsExceptionOnGusError(): void
    {
        // Arrange
        $nip = '1234567890';
        $query = new GetCompanyByNipQuery($nip);

        // Mock GUSApiClient - rzuca wyjątek
        $this->gusApiClient->expects($this->once())
            ->method('findCompanyByNip')
            ->with($nip)
            ->willThrowException(new \RuntimeException('Błąd połączenia z GUS'));

        // Mock logger
        $this->logger->expects($this->once())
            ->method('info')
            ->with('Pobieranie danych firmy z GUS', ['nip' => $nip]);

        $this->logger->expects($this->once())
            ->method('error')
            ->with(
                'Błąd podczas pobierania danych z GUS',
                $this->callback(function ($context) use ($nip) {
                    return $context['nip'] === $nip && isset($context['error']);
                })
            );

        $handler = new GetCompanyByNipHandler($this->gusApiClient, $this->logger);

        // Act & Assert
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Nie udało się pobrać danych firmy z GUS');

        $handler->handle($query);
    }
}

