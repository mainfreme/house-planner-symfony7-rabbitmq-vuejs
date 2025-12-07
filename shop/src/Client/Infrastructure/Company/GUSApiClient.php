<?php

declare(strict_types=1);

namespace App\Client\Infrastructure\Company;

use SoapClient;
use SoapFault;

class GUSApiClient
{
    private SoapClient $client;
    private string $sessionId;

    public function __construct(string $apiKey)
    {
        $this->client = new SoapClient('https://wyszukiwarkaregontest.stat.gov.pl/wsBIR/wsdl/UslugaBIRzewnPubl.xsd');
        $this->sessionId = $this->client->Zaloguj(['pKluczUzytkownika' => $apiKey]);
    }

    public function findCompanyByNip(string $nip): ?array
    {
        try {
            $params = ['pNip' => $nip];
            $response = $this->client->DaneSzukajPodmioty($params);
            
            if (empty($response->DaneSzukajPodmiotyResult)) {
                return null;
            }

            $result = $response->DaneSzukajPodmiotyResult;
            
            // Sprawdź czy wynik jest tablicą czy pojedynczym obiektem
            if (is_array($result->Dane)) {
                // Jeśli jest tablica, weź pierwszy element
                $data = $result->Dane[0] ?? null;
            } else {
                // Jeśli jest pojedynczy obiekt
                $data = $result->Dane ?? null;
            }

            if ($data === null) {
                return null;
            }

            // Konwertuj obiekt na tablicę
            return (array)$data;
        } catch (SoapFault $e) {
            throw new \RuntimeException('Błąd połączenia z GUS: ' . $e->getMessage(), 0, $e);
        }
    }
}
