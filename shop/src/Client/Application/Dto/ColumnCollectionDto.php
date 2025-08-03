<?php

declare(strict_types=1);

namespace App\Client\Application\Dto;

class ColumnCollectionDto
{
    /**
     * @param ColumnDto[] $columns
     */
    public function __construct(
        private readonly array $columns = []
    ) {}

    /**
     * Zwraca wszystkie kolumny
     *
     * @return ColumnDto[]
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
            fn(ColumnDto $col) => in_array($col->key, $keys, true)
        );

        return new self(array_values($filtered));
    }

    /**
     * Konwertuje kolekcję do tablicy (np. do JSON)
     */
    public function toArray(): array
    {
        return array_map(
            fn(ColumnDto $col) => $col->getArray(),
            $this->columns
        );
    }

}
