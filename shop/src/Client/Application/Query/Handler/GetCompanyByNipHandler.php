<?php

declare(strict_types=1);

namespace App\Client\Application\Query\Handler;

use App\Client\Application\Dto\CompanyByNipResponse;
use App\Client\Application\Query\GetCompanyByNipQuery;
use App\Client\Infrastructure\Company\GUSApiClient;
use Psr\Log\LoggerInterface;

class GetCompanyByNipHandler
{
    public function __construct(
        private readonly GUSApiClient $gusApiClient,
        private readonly LoggerInterface $logger
    ) {
    }

    public function handle(GetCompanyByNipQuery $query): CompanyByNipResponse
    {
        try {
            $this->logger->info('Pobieranie danych firmy z GUS', ['nip' => $query->nip]);
            
            // Pobierz dane z GUS
            $gusData = $this->gusApiClient->findCompanyByNip($query->nip);

            if ($gusData === null) {
                $this->logger->warning('Nie znaleziono firmy w GUS', ['nip' => $query->nip]);
                return new CompanyByNipResponse();
            }

            $this->logger->info('Pomyślnie pobrano dane firmy z GUS', ['nip' => $query->nip]);

            return new CompanyByNipResponse($gusData);
        } catch (\Exception $e) {
            $this->logger->error('Błąd podczas pobierania danych z GUS', [
                'nip' => $query->nip,
                'error' => $e->getMessage()
            ]);

            throw new \RuntimeException('Nie udało się pobrać danych firmy z GUS: ' . $e->getMessage(), 0, $e);
        }
    }
}

