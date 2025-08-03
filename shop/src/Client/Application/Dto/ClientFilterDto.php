<?php

declare(strict_types=1);

namespace App\Client\Application\Dto;

use Symfony\Component\Validator\Constraints as Assert;

final class ClientFilterDto
{
    #[Assert\NotBlank(message: 'Page cannot be blank.')]
    #[Assert\Positive(message: 'Page must be a positive number.')]
    #[Assert\Type(type: 'digit', message: 'Page must be numeric.')]
    private ?string $page = '1';

    #[Assert\Length(max: 255)]
    private ?string $name = null;

//    #[Assert\Regex(pattern: '/^\d{10}$/', message: 'NIP musi zawierać 10 cyfr.')]
    private ?string $nip = null;

//    #[Assert\Regex(pattern: '/^\d{9}$/', message: 'REGON musi zawierać 9 cyfr.')]
    private ?string $regon = null;

//    #[Assert\Regex(pattern: '/^\d{11}$/', message: 'REGON musi zawierać 11 cyfr.')]
    private ?string $pesel = null;

//    #[Assert\Email(message: 'Niepoprawny adres email')]
    #[Assert\Length(max: 255)]
    private ?string $email = null;

//    #[Assert\Regex(pattern: '/^\d{6,15}$/', message: 'Numer telefonu powinien zawierać od 6 do 15 cyfr.')]
    private ?int $phoneNumber = null;

    #[Assert\Length(max: 255)]
    private ?string $country = null;

    private ?string $is_delete = '';

    /**
     * @return string|null
     */
    public function getPage(): ?string
    {
        return $this->page;
    }

    /**
     * @param string|null $page
     * @return ClientFilterDto
     */
    public function setPage(?string $page): ClientFilterDto
    {
        $this->page = $page;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return ClientFilterDto
     */
    public function setName(?string $name): ClientFilterDto
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getNip(): ?string
    {
        return $this->nip;
    }

    /**
     * @param string|null $nip
     * @return ClientFilterDto
     */
    public function setNip(?string $nip): ClientFilterDto
    {
        $this->nip = $nip;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getRegon(): ?string
    {
        return $this->regon;
    }

    /**
     * @param string|null $regon
     * @return ClientFilterDto
     */
    public function setRegon(?string $regon): ClientFilterDto
    {
        $this->regon = $regon;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPesel(): ?string
    {
        return $this->pesel;
    }

    /**
     * @param string|null $pesel
     * @return ClientFilterDto
     */
    public function setPesel(?string $pesel): ClientFilterDto
    {
        $this->pesel = $pesel;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     * @return ClientFilterDto
     */
    public function setEmail(?string $email): ClientFilterDto
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getPhoneNumber(): ?int
    {
        return $this->phoneNumber;
    }

    /**
     * @param int|null $phoneNumber
     * @return ClientFilterDto
     */
    public function setPhoneNumber(?int $phoneNumber): ClientFilterDto
    {
        $this->phoneNumber = $phoneNumber;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * @param string|null $country
     * @return ClientFilterDto
     */
    public function setCountry(?string $country): ClientFilterDto
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getIsDelete(): ?string
    {
        return $this->is_delete;
    }

    /**
     * @param string|null $is_delete
     * @return ClientFilterDto
     */
    public function setIsDelete(?string $is_delete): ClientFilterDto
    {
        $this->is_delete = $is_delete;
        return $this;
    }


    public function getArray(): array
    {
        return get_object_vars($this);
    }
}
