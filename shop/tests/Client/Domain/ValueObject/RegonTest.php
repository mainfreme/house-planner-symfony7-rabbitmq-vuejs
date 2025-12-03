<?php

declare(strict_types=1);

namespace App\Tests\Client\Domain\ValueObject;

use App\Client\Domain\ValueObject\Regon;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class RegonTest extends TestCase
{
    public function testValid9DigitRegonIsAccepted(): void
    {
        $regon = new Regon('123456789');

        $this->assertInstanceOf(Regon::class, $regon);
        $this->assertEquals('123456789', $regon->value);
        $this->assertEquals(9, $regon->getLength());
        $this->assertTrue($regon->isShortRegon());
        $this->assertFalse($regon->isLongRegon());
    }

    public function testValid14DigitRegonIsAccepted(): void
    {
        $regon = new Regon('12345678901234');

        $this->assertInstanceOf(Regon::class, $regon);
        $this->assertEquals('12345678901234', $regon->value);
        $this->assertEquals(14, $regon->getLength());
        $this->assertFalse($regon->isShortRegon());
        $this->assertTrue($regon->isLongRegon());
    }

    public function testValidRegonWithSpacesIsAccepted(): void
    {
        $regon = new Regon('  123 456 789  ');

        $this->assertInstanceOf(Regon::class, $regon);
        $this->assertEquals('123456789', $regon->value);
    }

    public function testToStringReturnsValue(): void
    {
        $regon = new Regon('123456789');

        $this->assertEquals('123456789', (string) $regon);
    }

    public function testEqualsReturnsTrueForSameRegon(): void
    {
        $regon1 = new Regon('123456789');
        $regon2 = new Regon('123456789');

        $this->assertTrue($regon1->equals($regon2));
    }

    public function testEqualsReturnsFalseForDifferentRegon(): void
    {
        $regon1 = new Regon('123456789');
        $regon2 = new Regon('987654321');

        $this->assertFalse($regon1->equals($regon2));
    }

    public function testThrowsExceptionForInvalidLength(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('REGON musi składać się z 9 lub 14 cyfr.');

        new Regon('12345678');
    }

    public function testThrowsExceptionForNonDigits(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('REGON może zawierać tylko cyfry.');

        new Regon('12345678a');
    }

    public function testThrowsExceptionForInvalid9DigitChecksum(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Nieprawidłowa suma kontrolna REGON.');

        // REGON z nieprawidłową sumą kontrolną
        new Regon('123456785');
    }

    public function testThrowsExceptionForInvalid14DigitChecksum(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Nieprawidłowa suma kontrolna REGON.');

        // REGON z nieprawidłową sumą kontrolną
        new Regon('12345678901235');
    }

    /**
     * @dataProvider validRegonProvider
     */
    public function testValidRegons(string $regonValue, int $expectedLength): void
    {
        $regon = new Regon($regonValue);

        $this->assertInstanceOf(Regon::class, $regon);
        $this->assertEquals($regonValue, $regon->value);
        $this->assertEquals($expectedLength, $regon->getLength());
    }

    public function validRegonProvider(): array
    {
        return [
            // Przykładowe prawidłowe REGON-y 9-cyfrowe
            ['123456789', 9], // Test case
            ['010000000', 9], // Rzeczywisty przykład

            // Przykładowe prawidłowe REGON-y 14-cyfrowe
            ['12345678901234', 14], // Test case
            ['00000000000000', 14], // Rzeczywisty przykład
        ];
    }

    /**
     * @dataProvider invalidRegonProvider
     */
    public function testInvalidRegons(string $regonValue, string $expectedMessage): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($expectedMessage);

        new Regon($regonValue);
    }

    public function invalidRegonProvider(): array
    {
        return [
            ['12345678', 'REGON musi składać się z 9 lub 14 cyfr.'], // Za krótki
            ['1234567890', 'REGON musi składać się z 9 lub 14 cyfr.'], // Nieprawidłowa długość
            ['123456789012', 'REGON musi składać się z 9 lub 14 cyfr.'], // Nieprawidłowa długość
            ['1234567890123', 'REGON musi składać się z 9 lub 14 cyfr.'], // Nieprawidłowa długość
            ['123456789012345', 'REGON musi składać się z 9 lub 14 cyfr.'], // Za długi
            ['12345678a', 'REGON może zawierać tylko cyfry.'], // Litery
            ['123456785', 'Nieprawidłowa suma kontrolna REGON.'], // Nieprawidłowa suma kontrolna 9-cyfrowy
            ['12345678901235', 'Nieprawidłowa suma kontrolna REGON.'], // Nieprawidłowa suma kontrolna 14-cyfrowy
        ];
    }

    public function testValueIsReadonly(): void
    {
        $regon = new Regon('123456789');

        // Próba zmiany readonly property powinna zakończyć się błędem
        $this->expectError();
        $regon->value = '987654321';
    }

    public function testIsShortRegonReturnsTrueFor9Digit(): void
    {
        $regon = new Regon('123456789');

        $this->assertTrue($regon->isShortRegon());
        $this->assertFalse($regon->isLongRegon());
    }

    public function testIsLongRegonReturnsTrueFor14Digit(): void
    {
        $regon = new Regon('12345678901234');

        $this->assertFalse($regon->isShortRegon());
        $this->assertTrue($regon->isLongRegon());
    }
}
