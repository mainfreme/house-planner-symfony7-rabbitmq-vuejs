<?php

declare(strict_types=1);

namespace App\Client\Application\Dto;

use App\Shared\Application\Dto\FilterDtoInterface;
use DateTime;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;


final class ClientContactFilterDto implements FilterDtoInterface
{
    private ?int $clientId = null;

    #[Assert\Length(max: 100, maxMessage: "Imię może mieć maksymalnie {{ limit }} znaków.")]
    private ?string $name = null;

    #[Assert\Length(max: 100, maxMessage: "Nazwisko może mieć maksymalnie {{ limit }} znaków.")]
    private ?string $surname = null;

    #[Assert\Regex(
        pattern: '/^\d{2}-\d{3}$/',
        message: "Kod pocztowy musi być w formacie 00-000."
    )]
    private ?string $postal_code = null;

    #[Assert\Email(message: "Podaj poprawny adres email.")]
    private ?string $email = null;

    #[Assert\Regex(
        pattern: '/^\+?[0-9\s]{7,15}$/',
        message: "Numer telefonu może zawierać tylko cyfry, spacje i opcjonalnie +, 7–15 znaków."
    )]
    private ?string $phoneNumber = null;

    #[Assert\Country(message: "Podaj poprawny kod kraju ISO (np. PL, DE, US).")]
    private ?string $country = null;

    #[Assert\Language(message: "Podaj poprawny kod języka ISO (np. pl, en, de).")]
    private ?string $language = null;

    #[Assert\Type(\DateTimeInterface::class)]
    #[Assert\LessThanOrEqual("today", message: "Data dodania nie może być w przyszłości.")]
    private ?string $added_to = null;

//    /**
//     * Własna walidacja: email lub numer telefonu musi być podany
//     */
//    #[Assert\Callback]
//    public function validateEmailOrPhone(ExecutionContextInterface $context): void
//    {
//        if (empty($this->email) && empty($this->phoneNumber)) {
//            $context->buildViolation("Podaj email lub numer telefonu.")
//                ->atPath('email')
//                ->addViolation();
//        }
//    }



    public function getArray(): array
    {
        return get_object_vars($this);
    }

    /**
     * @return int|null
     */
    public function getClientId(): ?int
    {
        return $this->clientId;
    }

    /**
     * @param int|null $clientId
     * @return ClientContactFilterDto
     */
    public function setClientId(?int $clientId): ClientContactFilterDto
    {
        $this->clientId = $clientId;
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
     * @return ClientContactFilterDto
     */
    public function setName(?string $name): ClientContactFilterDto
    {
        $this->name = $name === '' ? null : $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSurname(): ?string
    {
        return $this->surname;
    }

    /**
     * @param string|null $surname
     * @return ClientContactFilterDto
     */
    public function setSurname(?string $surname): ClientContactFilterDto
    {
        $this->surname = $surname === '' ? null : $surname;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPostalCode(): ?string
    {
        return $this->postal_code;
    }

    /**
     * @param string|null $postal_code
     * @return ClientContactFilterDto
     */
    public function setPostalCode(?string $postal_code): ClientContactFilterDto
    {
        $this->postal_code = $postal_code === '' ? null : $postal_code;
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
     * @return ClientContactFilterDto
     */
    public function setEmail(?string $email): ClientContactFilterDto
    {
        $this->email = $email === '' ? null : $email;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    /**
     * @param string|null $phoneNumber
     * @return ClientContactFilterDto
     */
    public function setPhoneNumber(?string $phoneNumber): ClientContactFilterDto
    {
        $this->phoneNumber = $phoneNumber === '' ? null : $phoneNumber;
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
     * @return ClientContactFilterDto
     */
    public function setCountry(?string $country): ClientContactFilterDto
    {
        $this->country = $country === '' ? null : $country;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getLanguage(): ?string
    {
        return $this->language;
    }

    /**
     * @param string|null $language
     * @return ClientContactFilterDto
     */
    public function setLanguage(?string $language): ClientContactFilterDto
    {
        $this->language = $language === '' ? null : $language;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAddedTo(): ?string
    {
        return $this->added_to;
    }

    /**
     * @param string|null $added_to
     * @return ClientContactFilterDto
     */
    public function setAddedTo(?string $added_to): ClientContactFilterDto
    {
        $this->added_to = $added_to === '' ? null : $added_to;
        return $this;
    }

}
