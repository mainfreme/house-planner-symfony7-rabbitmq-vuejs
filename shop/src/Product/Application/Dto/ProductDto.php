<?php

declare(strict_types=1);

namespace App\Product\Application\Dto;

use App\Application\Shared\Dto\ArrayMappableDtoInterface;
use App\Application\Shared\Dto\ResponseDtoInterface;
use App\Product\Domain\Entity\Product;
use App\Product\Domain\Entity\ProductType;
use Ramsey\Uuid\UuidInterface;


final class ProductDto implements ResponseDtoInterface, ArrayMappableDtoInterface
{
    public function __construct(
        public UuidInterface $uuid,
        public string $name,
        public ?string $description,
        public string $price,
        public ProductTypeDto $type,
        public bool $isActive,
        public array $parameters,
        public ?UuidInterface $uuidImage = null,
        public ?string $dataImage = null,
        public ?array $parametersImage = null,
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

    public static function fromArray(array $productImages): self
    {
        $product = $productImages[0];

        return new self(
            uuid: $product->getUuid(),
            name: $product->getName(),
            description: $product->getDescription(),
            price: $product->getPrice(),
            type: self::mapType($product->getType()),
            isActive: $product->getIsActive(),
            parameters: $product->getParameters(),
            uuidImage: $productImages['uuid'],
            dataImage: $productImages['data'],
            parametersImage: $productImages['property'],
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

    /**
     * @return UuidInterface|null
     */
    public function getUuidImage(): ?UuidInterface
    {
        return $this->uuidImage;
    }

    /**
     * @param UuidInterface|null $uuidImage
     */
    public function setUuidImage(?UuidInterface $uuidImage): void
    {
        $this->uuidImage = $uuidImage;
    }

    /**
     * @return string|null
     */
    public function getDataImage(): ?string
    {
        return $this->dataImage;
    }

    /**
     * @param string|null $dataImage
     */
    public function setDataImage(?string $dataImage): void
    {
        $this->dataImage = $dataImage;
    }

    /**
     * @return array|null
     */
    public function getParametersImage(): ?array
    {
        return $this->parametersImage;
    }

    /**
     * @param array|null $parametersImage
     */
    public function setParametersImage(?array $parametersImage): void
    {
        $this->parametersImage = $parametersImage;
    }

}
