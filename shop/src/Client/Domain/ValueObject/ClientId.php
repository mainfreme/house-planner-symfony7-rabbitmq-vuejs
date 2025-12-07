<?php

declare(strict_types=1);

namespace App\Client\Domain\ValueObject;

use InvalidArgumentException;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class ClientId
{
    public function __construct(public readonly UuidInterface $value)
    {
    }

    /**
     * Tworzy nowy, losowy ClientId
     */
    public static function generate(): self
    {
        return new self(Uuid::uuid4());
    }

    /**
     * Tworzy ClientId z podanego stringa UUID
     */
    public static function fromString(string $uuid): self
    {
        if (!Uuid::isValid($uuid)) {
            throw new InvalidArgumentException('Podany string nie jest prawidłowym UUID.');
        }

        return new self(Uuid::fromString($uuid));
    }

    /**
     * Tworzy ClientId z istniejącego UuidInterface
     */
    public static function fromUuid(UuidInterface $uuid): self
    {
        return new self($uuid);
    }

    public function __toString(): string
    {
        return $this->value->toString();
    }

    public function equals(ClientId $other): bool
    {
        return $this->value->equals($other->value);
    }

    /**
     * Zwraca UUID w formacie string
     */
    public function toString(): string
    {
        return $this->value->toString();
    }

    /**
     * Sprawdza czy UUID jest nil (pusty)
     */
    public function isNil(): bool
    {
        return $this->value->getBytes() === Uuid::NIL;
    }

    /**
     * Zwraca UUID w formacie binarnym
     */
    public function getBytes(): string
    {
        return $this->value->getBytes();
    }

    /**
     * Zwraca UUID w formacie hex (bez myślników)
     */
    public function getHex(): string
    {
        return $this->value->getHex();
    }
}
