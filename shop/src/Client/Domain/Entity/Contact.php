<?php

declare(strict_types=1);

namespace App\Client\Domain\Entity;

use App\Client\Infrastructure\Persistence\Doctrine\ContactRepository;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: ContactRepository::class)]
class Contact
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $surname;

    #[ORM\Column(length: 100)]
    private ?string $email = null;

    #[ORM\Column(length: 100)]
    private ?string $phoneNumber = null;

    #[ORM\Column(length: 100)]
    private string $country = '';

    #[ORM\Column(length: 100)]
    private string $language = '';

    #[ORM\Column(length: 10)]
    private ?string $areaCode = null;

    #[ORM\Column(type: Types::TEXT)]
    private string $note = '';

    #[ORM\Column]
    private ?\DateTimeImmutable $added_at = null;

    #[ORM\JoinColumn(nullable: false)]
    private ?Client $client = null;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return Contact
     */
    public function setId(?int $id): Contact
    {
        $this->id = $id;
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
     * @return Contact
     */
    public function setName(?string $name): Contact
    {
        $this->name = $name;
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
     * @return Contact
     */
    public function setSurname(?string $surname): Contact
    {
        $this->surname = $surname;
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
     * @return Contact
     */
    public function setEmail(?string $email): Contact
    {
        $this->email = $email;
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
     * @return Contact
     */
    public function setPhoneNumber(?string $phoneNumber): Contact
    {
        $this->phoneNumber = $phoneNumber;
        return $this;
    }

    /**
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * @param string $country
     * @return Contact
     */
    public function setCountry(string $country): Contact
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @return string
     */
    public function getLanguage(): string
    {
        return $this->language;
    }

    /**
     * @param string $language
     * @return Contact
     */
    public function setLanguage(string $language): Contact
    {
        $this->language = $language;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAreaCode(): ?string
    {
        return $this->areaCode;
    }

    /**
     * @param string|null $areaCode
     * @return Contact
     */
    public function setAreaCode(?string $areaCode): Contact
    {
        $this->areaCode = $areaCode;
        return $this;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getAddedAt(): ?\DateTimeImmutable
    {
        return $this->added_at;
    }

    /**
     * @return string
     */
    public function getNote(): string
    {
        return $this->note;
    }

    /**
     * @param string $note
     * @return Contact
     */
    public function setNote(string $note): Contact
    {
        $this->note = $note;
        return $this;
    }

    /**
     * @param \DateTimeImmutable|null $added_at
     * @return Contact
     */
    public function setAddedAt(?\DateTimeImmutable $added_at): Contact
    {
        $this->added_at = $added_at;
        return $this;
    }

    /**
     * @return Client|null
     */
    public function getClient(): ?Client
    {
        return $this->client;
    }

    /**
     * @param Client|null $client
     * @return Contact
     */
    public function setClient(?Client $client): Contact
    {
        $this->client = $client;
        return $this;
    }
}
