<?php

declare(strict_types=1);

namespace App\Client\Application\UI\Http\Request;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class CreateClientRequest
{
    #[Assert\NotBlank(message: "Nazwa klienta jest wymagana.")]
    public string $name;

    #[Assert\NotBlank(message: "NIP jest wymagany.")]
    #[Assert\Regex(pattern: '/^[0-9]{10}$/', message: "NIP musi składać się z 10 cyfr.")]
    public string $nip;

    #[Assert\NotBlank(message: "Email jest wymagany.")]
    #[Assert\Email(message: "Niepoprawny adres e-mail.")]
    public string $email;

    public function __construct(string $name, string $nip, string $email)
    {
        $this->name = $name;
        $this->nip = $nip;
        $this->email = $email;
    }
}
