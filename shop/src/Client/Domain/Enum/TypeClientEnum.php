<?php

declare(strict_types=1);

namespace App\Client\Domain\Enum;

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
