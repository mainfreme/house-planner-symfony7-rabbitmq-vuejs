<?php

declare(strict_types=1);

namespace App\Tests\Client\Domain\ValueObject;

use App\Client\Domain\ValueObject\ClientId;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class ClientIdTest extends TestCase
{
    public function testGenerateCreatesValidClientId(): void
    {
        $clientId = ClientId::generate();

        $this->assertInstanceOf(ClientId::class, $clientId);
        $this->assertNotNull($clientId->value);
        $this->assertTrue(Uuid::isValid($clientId->toString()));
    }

    public function testFromStringCreatesValidClientId(): void
    {
        $uuidString = '550e8400-e29b-41d4-a716-446655440000';
        $clientId = ClientId::fromString($uuidString);

        $this->assertInstanceOf(ClientId::class, $clientId);
        $this->assertEquals($uuidString, $clientId->toString());
    }

    public function testFromStringThrowsExceptionForInvalidUuid(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Podany string nie jest prawidłowym UUID.');

        ClientId::fromString('invalid-uuid-string');
    }

    public function testFromUuidCreatesValidClientId(): void
    {
        $uuid = Uuid::uuid4();
        $clientId = ClientId::fromUuid($uuid);

        $this->assertInstanceOf(ClientId::class, $clientId);
        $this->assertEquals($uuid, $clientId->value);
    }

    public function testToStringReturnsStringRepresentation(): void
    {
        $uuidString = '550e8400-e29b-41d4-a716-446655440000';
        $clientId = ClientId::fromString($uuidString);

        $this->assertEquals($uuidString, $clientId->toString());
        $this->assertEquals($uuidString, (string) $clientId);
    }

    public function testEqualsReturnsTrueForSameUuid(): void
    {
        $uuidString = '550e8400-e29b-41d4-a716-446655440000';
        $clientId1 = ClientId::fromString($uuidString);
        $clientId2 = ClientId::fromString($uuidString);

        $this->assertTrue($clientId1->equals($clientId2));
    }

    public function testEqualsReturnsFalseForDifferentUuid(): void
    {
        $clientId1 = ClientId::fromString('550e8400-e29b-41d4-a716-446655440000');
        $clientId2 = ClientId::fromString('650e8400-e29b-41d4-a716-446655440000');

        $this->assertFalse($clientId1->equals($clientId2));
    }

    public function testIsNilReturnsFalseForRegularUuid(): void
    {
        $clientId = ClientId::generate();

        $this->assertFalse($clientId->isNil());
    }

    public function testIsNilReturnsTrueForNilUuid(): void
    {
        $clientId = ClientId::fromString(Uuid::NIL);

        $this->assertTrue($clientId->isNil());
    }

    public function testGetBytesReturnsBinaryRepresentation(): void
    {
        $uuidString = '550e8400-e29b-41d4-a716-446655440000';
        $clientId = ClientId::fromString($uuidString);

        $bytes = $clientId->getBytes();
        $this->assertIsString($bytes);
        $this->assertEquals(16, strlen($bytes)); // UUID ma 16 bajtów
    }

    public function testGetHexReturnsHexRepresentation(): void
    {
        $uuidString = '550e8400-e29b-41d4-a716-446655440000';
        $clientId = ClientId::fromString($uuidString);

        $hex = $clientId->getHex();
        $this->assertIsString($hex);
        $this->assertEquals(32, strlen($hex)); // UUID bez myślników ma 32 znaki hex
        $this->assertEquals('550e8400e29b41d4a716446655440000', $hex);
    }

    public function testValueIsReadonly(): void
    {
        $uuid = Uuid::uuid4();
        $clientId = ClientId::fromUuid($uuid);

        // Próba zmiany readonly property powinna zakończyć się błędem
        $this->expectError();
        $clientId->value = Uuid::uuid4();
    }
}
