<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

final class Nip
{
    public function __construct(public string $value)
    {
        $this->validate($value);
    }

    private function validate(string $nip): void
    {
        if (!preg_match('/^[0-9]{10}$/', $nip)) {
            throw new \InvalidArgumentException("Niepoprawny NIP: $nip");
        }
    }
}
