<?php

declare(strict_types=1);

namespace App\Product\Application\Dto;

use Symfony\Component\Validator\Constraints as Assert;

final class ProductFilterDto
{
    #[Assert\NotBlank(message: 'Page cannot be blank.')]
    #[Assert\Positive(message: 'Page must be a positive number.')]
    #[Assert\Type(type: 'digit', message: 'Page must be numeric.')]
    private ?string $page = '1';

    #[Assert\Length(
        max: 255,
        maxMessage: 'Name cannot be longer than {{ limit }} characters.'
    )]
    private ?string $name = null;

    #[Assert\Type(type: 'digit', message: 'Minimum price must be numeric.')]
    #[Assert\GreaterThanOrEqual(value: 0, message: 'Minimum price cannot be negative.')]
    private ?string $priceMin = null;

    #[Assert\Type(type: 'digit', message: 'Maximum price must be numeric.')]
    #[Assert\GreaterThanOrEqual(value: 0, message: 'Maximum price cannot be negative.')]
    private ?string $priceMax = null;

    private ?string $type = null;

    #[Assert\Type(type: 'bool', message: 'isActive must be a boolean.')]
    private ?bool $isActive = null;

    /**
     * @return ?string
     */
    public function getPage(): ?string
    {
        return $this->page;
    }

    /**
     * @param ?string $page
     */
    public function setPage(?string $page): void
    {
        $this->page = $page;
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
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getPriceMin(): ?string
    {
        return $this->priceMin;
    }

    /**
     * @param string|null $priceMin
     */
    public function setPriceMin(?string $priceMin): void
    {
        $this->priceMin = $priceMin;
    }

    /**
     * @return string|null
     */
    public function getPriceMax(): ?string
    {
        return $this->priceMax;
    }

    /**
     * @param string|null $priceMax
     */
    public function setPriceMax(?string $priceMax): void
    {
        $this->priceMax = $priceMax;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string|null $type
     */
    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return bool|null
     */
    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    /**
     * @param bool|null $isActive
     */
    public function setIsActive(?bool $isActive): void
    {
        $this->isActive = $isActive;
    }

    /**
     * @return array<int|string|null>
     */
    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
