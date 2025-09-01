<?php

declare(strict_types=1);

namespace App\Shared\Application\Dto;

class ColumnArray implements ResponseDtoInterface, ArrayMappableInterface
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

    public function toApiArray(): array
    {
        // TODO: Implement toApiArray() method.
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
