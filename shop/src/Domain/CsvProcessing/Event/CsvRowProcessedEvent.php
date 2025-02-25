<?php

namespace App\Domain\CsvProcessing\Event;

class CsvRowProcessedEvent
{
    public function __construct(
        private readonly string  $id,
        private readonly string  $firstName,
        private readonly string  $lastName,
        private readonly string  $email,
        private readonly string  $name,
        private readonly string  $filename,
        private readonly int     $row,
        private readonly ?string $error = null
    )
    {}

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getFilename(): string
    {
        return $this->filename;
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
     * @return array<int|string>
     */
    public function getData(): array
    {
        return [$this->id, $this->firstName, $this->lastName, $this->email, $this->name];
    }
}
