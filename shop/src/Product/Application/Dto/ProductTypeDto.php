<?php

declare(strict_types=1);

namespace App\Product\Application\Dto;

use App\Application\Shared\Dto\ResponseDtoInterface;

class ProductTypeDto implements ResponseDtoInterface
{

    private int $id;

    private string $name;

    private string $link;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
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
     * @return string
     */
    public function getLink(): string
    {
        return $this->link;
    }

    /**
     * @param string $link
     */
    public function setLink(string $link): void
    {
        $this->link = $link;
    }

    /**
     * @return array<string>
     */
    public function getArray(): array
    {
        return get_object_vars($this);
    }
}
