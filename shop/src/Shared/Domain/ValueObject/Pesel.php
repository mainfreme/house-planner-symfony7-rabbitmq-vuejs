<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

final class Pesel
{
    public function __construct(public string $pesel)
    {
        $this->validate($this->pesel);
    }

    public function validate(string $pesel)
    {

    }
}
