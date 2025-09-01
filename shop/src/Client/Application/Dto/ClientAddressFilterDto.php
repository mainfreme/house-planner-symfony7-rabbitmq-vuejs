<?php

declare(strict_types=1);

namespace App\Client\Application\Dto;

use App\Shared\Application\Dto\FilterDtoInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class ClientAddressFilterDto implements FilterDtoInterface
{
    #[Assert\NotBlank(message: 'Page cannot be blank.')]
    #[Assert\Positive(message: 'Page must be a positive number.')]
    #[Assert\Type(type: 'digit', message: 'Page must be numeric.')]
    private ?string $page = '1';

    private ?int $clientId = null;

    private ?string $street = null;
    private ?string $postal_code = null;
    private ?string $city = null;
    private ?string $state_province = null;
    private ?string $country = null;
    private ?string $additional_info = null;
    private ?string $house_number = null;
    private ?string $apartment_number = null;
    private ?bool $is_primary = null;
    private ?string $added_at = null;

    public function getArray(): array
    {
        return get_object_vars($this);
    }

    /**
     * @return string|null
     */
    public function getPage(): ?string
    {
        return $this->page;
    }

    /**
     * @param string|null $page
     * @return ClientAddressFilterDto
     */
    public function setPage(?string $page): ClientAddressFilterDto
    {
        $this->page = $page;
        return $this;
    }

    public function getClientId(): ?int
    {
        return $this->clientId;
    }

    public function setClientId(?int $clientId): ClientAddressFilterDto
    {
        $this->clientId = $clientId;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getStreet(): ?string
    {
        return $this->street;
    }

    /**
     * @param string|null $street
     * @return ClientAddressFilterDto
     */
    public function setStreet(?string $street): ClientAddressFilterDto
    {
        $this->street = $street;
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
     * @return ClientAddressFilterDto
     */
    public function setPostalCode(?string $postal_code): ClientAddressFilterDto
    {
        $this->postal_code = $postal_code;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param string|null $city
     * @return ClientAddressFilterDto
     */
    public function setCity(?string $city): ClientAddressFilterDto
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getStateProvince(): ?string
    {
        return $this->state_province;
    }

    /**
     * @param string|null $state_province
     * @return ClientAddressFilterDto
     */
    public function setStateProvince(?string $state_province): ClientAddressFilterDto
    {
        $this->state_province = $state_province;
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
     * @return ClientAddressFilterDto
     */
    public function setCountry(?string $country): ClientAddressFilterDto
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAdditionalInfo(): ?string
    {
        return $this->additional_info;
    }

    /**
     * @param string|null $additional_info
     * @return ClientAddressFilterDto
     */
    public function setAdditionalInfo(?string $additional_info): ClientAddressFilterDto
    {
        $this->additional_info = $additional_info;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getHouseNumber(): ?string
    {
        return $this->house_number;
    }

    /**
     * @param string|null $house_number
     * @return ClientAddressFilterDto
     */
    public function setHouseNumber(?string $house_number): ClientAddressFilterDto
    {
        $this->house_number = $house_number;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getApartmentNumber(): ?string
    {
        return $this->apartment_number;
    }

    /**
     * @param string|null $apartment_number
     * @return ClientAddressFilterDto
     */
    public function setApartmentNumber(?string $apartment_number): ClientAddressFilterDto
    {
        $this->apartment_number = $apartment_number;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getIsPrimary(): ?bool
    {
        return $this->is_primary;
    }

    /**
     * @param bool|null $is_primary
     * @return ClientAddressFilterDto
     */
    public function setIsPrimary(?bool $is_primary): ClientAddressFilterDto
    {
        $this->is_primary = $is_primary;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAddedAt(): ?string
    {
        return $this->added_at;
    }

    /**
     * @param string|null $added_at
     * @return ClientAddressFilterDto
     */
    public function setAddedAt(?string $added_at): ClientAddressFilterDto
    {
        $this->added_at = $added_at;
        return $this;
    }


}
