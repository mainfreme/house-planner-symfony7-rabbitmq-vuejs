<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

final class Regon
{
    public function __construct(public string $regon)
    {
        $this->validate($this->regon);
    }

    private function validate(string $regon): void
    {
        if (!preg_match('/^[0-9]{10}$/', $regon)) {
            throw new \InvalidArgumentException("Niepoprawny REGON: $regon");
        }
    }
}
