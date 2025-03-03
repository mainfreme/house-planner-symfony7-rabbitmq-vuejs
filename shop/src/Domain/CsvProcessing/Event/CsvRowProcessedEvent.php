<?php

namespace App\Domain\CsvProcessing\Event;

class CsvRowProcessedEvent
{
    public function __construct(
        private readonly float|int|string $id,
        private readonly mixed            $fullName,
        private readonly mixed            $email,
        private readonly mixed            $name,
        private readonly string           $uuid,
        private readonly int              $row,
        private readonly int              $totalRow,
        private readonly ?string          $error = null
    ) {
    }

    /**
     * @return mixed
     */
    public function getEmail(): mixed
    {
        return $this->email;
    }

    /**
     * @return float|int|string
     */
    public function getId(): float|int|string
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getFullName(): mixed
    {
        return $this->fullName;
    }

    /**
     * @return mixed
     */
    public function getName(): mixed
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * @return int
     */
    public function getRow(): int
    {
        return $this->row;
    }

    /**
     * @return string|null
     */
    public function getError(): ?string
    {
        return $this->error;
    }

    /**
     * @return array<int|mixed>
     */
    public function getData(): array
    {
        return [$this->id, $this->fullName, $this->email, $this->name];
    }

    /**
     * @return int
     */
    public function getTotalRow(): int
    {
        return $this->totalRow;
    }
}
