<?php

declare(strict_types=1);

namespace App\Product\Application\Dto;

use App\Application\Shared\Dto\ResponseDtoInterface;
use App\Product\Domain\Entity\Product;
use App\Product\Domain\Entity\ProductType;
use Ramsey\Uuid\UuidInterface;


final class ProductDto implements ResponseDtoInterface
{
    public function __construct(
        public UuidInterface $uuid,
        public string $name,
        public ?string $description,
        public string $price,
        public ProductTypeDto $type,
        public bool $isActive,
        public array $parameters,
    ) {}

    public static function fromEntity(Product $product): self
    {
        return new self(
            uuid: $product->getUuid(),
            name: $product->getName(),
            description: $product->getDescription(),
            price: $product->getPrice(),
            type: self::mapType($product->getType()),
            isActive: $product->getIsActive(),
            parameters: $product->getParameters(),
        );
    }

    private static function mapType(ProductType $type): ProductTypeDto
    {
        $dto = new ProductTypeDto();
        $dto->setId($type->getId());
        $dto->setName($type->getName());

        return $dto;
    }

    /**
     * @return UuidInterface
     */
    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    /**
     * @param UuidInterface $uuid
     */
    public function setUuid(UuidInterface $uuid): void
    {
        $this->uuid = $uuid;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getPrice(): string
    {
        return $this->price;
    }

    /**
     * @param string $price
     */
    public function setPrice(string $price): void
    {
        $this->price = $price;
    }

    /**
     * @return ProductTypeDto
     */
    public function getType(): ProductTypeDto
    {
        return $this->type;
    }

    /**
     * @param ProductTypeDto $type
     */
    public function setType(ProductTypeDto $type): void
    {
        $this->type = $type;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->isActive;
    }

    /**
     * @param bool $isActive
     */
    public function setIsActive(bool $isActive): void
    {
        $this->isActive = $isActive;
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @param array $parameters
     */
    public function setParameters(array $parameters): void
    {
        $this->parameters = $parameters;
    }

    public function getArray(): array
    {
        return get_object_vars($this);
    }
}
