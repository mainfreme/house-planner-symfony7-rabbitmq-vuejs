<?php

declare(strict_types=1);

namespace App\Client\Application\Dto;

use App\Application\Shared\Dto\ArrayMappableDtoInterface;
use App\Application\Shared\Dto\ResponseDtoInterface;

class ColumnDto implements ResponseDtoInterface, ArrayMappableDtoInterface
{
    public function __construct(
        public string $key,
        public string $label,
    )
    {
    }

    public static function fromArray(array $array): self
    {
        return new self(
            key: $array['key'],
            label: $array['label'],
        );
    }

    public function getArray(): array
    {
        return get_object_vars($this);
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @param string $key
     * @return self
     */
    public function setKey(string $key): self
    {
        $this->key = $key;
        return $this;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @param string $label
     * @return self
     */
    public function setLabel(string $label): self
    {
        $this->label = $label;
        return $this;
    }


}
