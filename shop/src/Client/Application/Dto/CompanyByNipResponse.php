<?php

declare(strict_types=1);

namespace App\Client\Application\Dto;

class CompanyByNipResponse
{
    public ?string $name = null;
    public ?string $nip = null;
    public ?string $regon = null;
    public ?string $street = null;
    public ?string $houseNumber = null;
    public ?string $apartmentNumber = null;
    public ?string $postalCode = null;
    public ?string $city = null;
    public ?string $country = null;
    public ?string $email = null;
    public ?string $phone = null;

    public function __construct(array|object $gusData = [])
    {
        if (empty($gusData)) {
            return;
        }

        // Konwertuj obiekt na tablicę jeśli potrzeba
        if (is_object($gusData)) {
            $gusData = (array)$gusData;
        }

        // Mapowanie pól z GUS (różne możliwe nazwy pól)
        $this->name = $gusData['Nazwa'] ?? $gusData['nazwa'] ?? $gusData['NazwaPelna'] ?? null;
        $this->nip = $gusData['Nip'] ?? $gusData['nip'] ?? null;
        $this->regon = $gusData['Regon'] ?? $gusData['regon'] ?? null;
        $this->street = $gusData['Ulica'] ?? $gusData['ulica'] ?? null;
        $this->houseNumber = $gusData['NrNieruchomosci'] ?? $gusData['NrNieruchomosci'] ?? $gusData['NumerNieruchomosci'] ?? null;
        $this->apartmentNumber = $gusData['NrLokalu'] ?? $gusData['NrLokalu'] ?? $gusData['NumerLokalu'] ?? null;
        $this->postalCode = $gusData['KodPocztowy'] ?? $gusData['kodPocztowy'] ?? null;
        $this->city = $gusData['Miejscowosc'] ?? $gusData['miejscowosc'] ?? $gusData['MiejscowoscPelna'] ?? null;
        $this->country = 'Polska'; // GUS zwraca tylko polskie firmy
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'nip' => $this->nip,
            'regon' => $this->regon,
            'street' => $this->street,
            'houseNumber' => $this->houseNumber,
            'apartmentNumber' => $this->apartmentNumber,
            'postalCode' => $this->postalCode,
            'city' => $this->city,
            'country' => $this->country,
            'email' => $this->email,
            'phone' => $this->phone,
        ];
    }
}

