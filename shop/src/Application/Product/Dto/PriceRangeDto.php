<?php

declare(strict_types=1);

namespace App\Application\Product\Dto;

class PriceRangeDto
{
    private float $minPrice;

    private float $maxPrice;

    /**
     * @return float
     */
    public function getMinPrice(): float
    {
        return $this->minPrice;
    }

    /**
     * @param float $minPrice
     */
    public function setMinPrice(float $minPrice): void
    {
        $this->minPrice = $minPrice;
    }

    /**
     * @return float
     */
    public function getMaxPrice(): float
    {
        return $this->maxPrice;
    }

    /**
     * @param float $maxPrice
     */
    public function setMaxPrice(float $maxPrice): void
    {
        $this->maxPrice = $maxPrice;
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
