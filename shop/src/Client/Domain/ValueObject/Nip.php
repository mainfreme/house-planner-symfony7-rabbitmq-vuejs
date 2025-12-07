<?php

declare(strict_types=1);

namespace App\Client\Domain\ValueObject;

use InvalidArgumentException;

final class Nip
{
    private const NIP_LENGTH = 10;
    private const WEIGHTS = [6, 5, 7, 2, 3, 4, 5, 2, 7, 6];

    public function __construct(public readonly string $value)
    {
        $this->validate($value);
    }

    private function validate(string $nip): void
    {
        // Usuń wszystkie białe znaki
        $nip = preg_replace('/\s+/', '', $nip);

        // Sprawdź długość
        if (strlen($nip) !== self::NIP_LENGTH) {
            throw new InvalidArgumentException('NIP musi składać się z dokładnie 10 cyfr.');
        }

        // Sprawdź czy zawiera tylko cyfry
        if (!ctype_digit($nip)) {
            throw new InvalidArgumentException('NIP może zawierać tylko cyfry.');
        }

        // Sprawdź sumę kontrolną
        if (!$this->isValidChecksum($nip)) {
            throw new InvalidArgumentException('Nieprawidłowa suma kontrolna NIP.');
        }
    }

    private function isValidChecksum(string $nip): bool
    {
        $digits = str_split($nip);
        $sum = 0;

        // Oblicz sumę ważoną dla pierwszych 9 cyfr
        for ($i = 0; $i < 9; $i++) {
            $sum += (int)$digits[$i] * self::WEIGHTS[$i];
        }

        $checksum = $sum % 11;

        // Jeśli suma kontrolna wynosi 10, NIP jest nieprawidłowy
        if ($checksum === 10) {
            return false;
        }

        // Sprawdź czy suma kontrolna zgadza się z ostatnią cyfrą
        return $checksum === (int)$digits[9];
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function equals(Nip $other): bool
    {
        return $this->value === $other->value;
    }

    /**
     * Formatuje NIP w standardowym formacie (XXX-XXX-XX-XX)
     */
    public function format(): string
    {
        return sprintf(
            '%s-%s-%s-%s',
            substr($this->value, 0, 3),
            substr($this->value, 3, 3),
            substr($this->value, 6, 2),
            substr($this->value, 8, 2)
        );
    }
}