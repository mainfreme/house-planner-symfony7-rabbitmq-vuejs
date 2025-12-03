<?php

declare(strict_types=1);

namespace App\Client\Application\Query;

use Symfony\Component\Validator\Constraints as Assert;

class GetCompanyByNipQuery
{
    #[Assert\NotBlank(message: "NIP nie może być pusty")]
    #[Assert\Length(
        min: 10,
        max: 10,
        exactMessage: "NIP musi składać się z dokładnie 10 cyfr"
    )]
    #[Assert\Regex(
        pattern: "/^\d+$/",
        message: "NIP może zawierać tylko cyfry"
    )]
    public string $nip;

    public function __construct(string $nip)
    {
        $this->nip = $nip;
    }
}

