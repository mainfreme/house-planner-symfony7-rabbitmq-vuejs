<?php

declare(strict_types=1);

namespace App\Client\Domain\ValueObject;

use InvalidArgumentException;

final class Regon
{
    private const REGON_LENGTH_9 = 9;
    private const REGON_LENGTH_14 = 14;
    private const WEIGHTS_9 = [8, 9, 2, 3, 4, 5, 6, 7];
    private const WEIGHTS_14 = [2, 4, 8, 5, 0, 9, 7, 3, 6, 1, 2, 4, 8];

    public function __construct(public readonly string $value)
    {
        $this->validate($value);
    }

    private function validate(string $regon): void
    {
        // Usuń wszystkie białe znaki
        $regon = preg_replace('/\s+/', '', $regon);

        // Sprawdź długość
        if (strlen($regon) !== self::REGON_LENGTH_9 && strlen($regon) !== self::REGON_LENGTH_14) {
            throw new InvalidArgumentException('REGON musi składać się z 9 lub 14 cyfr.');
        }

        // Sprawdź czy zawiera tylko cyfry
        if (!ctype_digit($regon)) {
            throw new InvalidArgumentException('REGON może zawierać tylko cyfry.');
        }

        // Sprawdź sumę kontrolną
        if (!$this->isValidChecksum($regon)) {
            throw new InvalidArgumentException('Nieprawidłowa suma kontrolna REGON.');
        }
    }

    private function isValidChecksum(string $regon): bool
    {
        $length = strlen($regon);
        $digits = str_split($regon);

        if ($length === self::REGON_LENGTH_9) {
            // Dla REGON 9-cyfrowego
            $sum = 0;
            for ($i = 0; $i < 8; $i++) {
                $sum += (int)$digits[$i] * self::WEIGHTS_9[$i];
            }
            $checksum = $sum % 11;
            if ($checksum === 10) {
                $checksum = 0;
            }
            return $checksum === (int)$digits[8];
        } else {
            // Dla REGON 14-cyfrowego
            $sum = 0;
            for ($i = 0; $i < 13; $i++) {
                $sum += (int)$digits[$i] * self::WEIGHTS_14[$i];
            }
            $checksum = $sum % 11;
            if ($checksum === 10) {
                $checksum = 0;
            }
            return $checksum === (int)$digits[13];
        }
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function equals(Regon $other): bool
    {
        return $this->value === $other->value;
    }

    /**
     * Zwraca długość REGON (9 lub 14)
     */
    public function getLength(): int
    {
        return strlen($this->value);
    }

    /**
     * Sprawdza czy to REGON 9-cyfrowy
     */
    public function isShortRegon(): bool
    {
        return strlen($this->value) === self::REGON_LENGTH_9;
    }

    /**
     * Sprawdza czy to REGON 14-cyfrowy
     */
    public function isLongRegon(): bool
    {
        return strlen($this->value) === self::REGON_LENGTH_14;
    }
}