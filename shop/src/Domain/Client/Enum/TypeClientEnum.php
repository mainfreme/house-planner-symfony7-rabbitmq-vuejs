<?php

namespace App\Domain\Client\Enum;

enum TypeClientEnum: string
{
    case INDIVIDUAL = 'individual';
    case BUSINESS = 'business';

    public function label(): string
    {
        return match ($this) {
            self::INDIVIDUAL => 'Klient indywidualny',
            self::BUSINESS => 'Klient biznesowy',
        };
    }
}
