<?php

declare(strict_types=1);

namespace App\Shared\Application\Dto;

class ColumnCollectionDto implements ResponseDtoInterface
{
    /**
     * @param ColumnArray[] $columns
     */
    public function __construct(
        private readonly array $columns = []
    ) {}

    /**
     * Zwraca wszystkie kolumny
     *
     * @return ColumnArray[]
     */
    public function all(): array
    {
        return $this->columns;
    }

    /**
     * Filtruje kolumny na podstawie listy kluczy
     *
     * @param string[] $keys
     * @return self
     */
    public function only(array $keys): self
    {
        $filtered = array_filter(
            $this->columns,
            fn(ColumnArray $col) => in_array($col->key, $keys, true)
        );

        return new self(array_values($filtered));
    }

    /**
     * @param string $onlyField
     * @return array
     */
    public function onlyFiled(string $onlyField = 'key'): array
    {
        return array_map(
            fn(ColumnArray $col) => $col->{'get' . ucfirst($onlyField)}(),
            $this->columns
        );
    }

    /**
     * Konwertuje kolekcję do tablicy (np. do JSON)
     */
    public function toArray(?string $onlyField = null): array
    {
        if (NULL !== $onlyField) {
            return $this->onlyFiled($onlyField);
        }

        return array_map(
            fn(ColumnArray $col) => $col->getArray(),
            $this->columns
        );
    }

    public function getArray(): array
    {
        return get_object_vars($this);
    }

    /**
     * @deprecated
     * @return array
     */
    public function toApiArray(): array
    {
        // TODO: Implement toApiArray() method.
    }
}
