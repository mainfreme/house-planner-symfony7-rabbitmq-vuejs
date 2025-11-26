<?php

declare(strict_types=1);

namespace App\Infrastructure\Fakturownia\Api;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class FakturowniaApiClient
{
    private const BASE_URL = 'https://bialonj.fakturownia.pl';
    private const CLIENTS_ENDPOINT = '/clients.json';
    private string $apiToken;

    public function __construct(HttpClientInterface $httpClient, string $apiToken)
    {
        $this->httpClient = $httpClient;
        $this->apiToken = $apiToken;
    }

    public function getClients(int $page = 1, int $perPage = 25): array
    {
        $response = $this->httpClient->request('GET', self::BASE_URL . self::CLIENTS_ENDPOINT, [
            'query' => [
                'page' => $page,
                'per_page' => $perPage,
                'api_token' => $this->apiToken,
            ],
        ]);

        // Sprawdzenie statusu odpowiedzi, obsługa błędów, itp.
        if ($response->getStatusCode() !== Response::HTTP_OK) {
            throw new \Exception('');
        }

        // Fakturownia zwraca tablicę klientów bezpośrednio jako główny element JSON,
        // więc zwracamy zdekodowany kontent.
        return $response->toArray();
    }
}
