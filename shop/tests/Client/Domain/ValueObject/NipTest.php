<?php

declare(strict_types=1);

namespace App\Tests\Client\Domain\ValueObject;

use App\Client\Domain\ValueObject\Nip;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class NipTest extends TestCase
{
    public function testValidNipIsAccepted(): void
    {
        // Przykładowy prawidłowy NIP: 123-456-78-90 (suma kontrolna jest prawidłowa dla tego przykładu)
        $nip = new Nip('1234567890');

        $this->assertInstanceOf(Nip::class, $nip);
        $this->assertEquals('1234567890', $nip->value);
    }

    public function testValidNipWithSpacesIsAccepted(): void
    {
        $nip = new Nip('  123 456 78 90  ');

        $this->assertInstanceOf(Nip::class, $nip);
        $this->assertEquals('1234567890', $nip->value);
    }

    public function testFormatReturnsFormattedNip(): void
    {
        $nip = new Nip('1234567890');

        $this->assertEquals('123-456-78-90', $nip->format());
    }

    public function testToStringReturnsValue(): void
    {
        $nip = new Nip('1234567890');

        $this->assertEquals('1234567890', (string) $nip);
    }

    public function testEqualsReturnsTrueForSameNip(): void
    {
        $nip1 = new Nip('1234567890');
        $nip2 = new Nip('1234567890');

        $this->assertTrue($nip1->equals($nip2));
    }

    public function testEqualsReturnsFalseForDifferentNip(): void
    {
        $nip1 = new Nip('1234567890');
        $nip2 = new Nip('0987654321');

        $this->assertFalse($nip1->equals($nip2));
    }

    public function testThrowsExceptionForTooShortNip(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('NIP musi składać się z dokładnie 10 cyfr.');

        new Nip('123456789');
    }

    public function testThrowsExceptionForTooLongNip(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('NIP musi składać się z dokładnie 10 cyfr.');

        new Nip('12345678901');
    }

    public function testThrowsExceptionForNonDigits(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('NIP może zawierać tylko cyfry.');

        new Nip('123456789a');
    }

    public function testThrowsExceptionForInvalidChecksum(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Nieprawidłowa suma kontrolna NIP.');

        // NIP z nieprawidłową sumą kontrolną
        new Nip('1234567899');
    }

    /**
     * @dataProvider validNipProvider
     */
    public function testValidNips(string $nipValue): void
    {
        $nip = new Nip($nipValue);

        $this->assertInstanceOf(Nip::class, $nip);
        $this->assertEquals($nipValue, $nip->value);
    }

    public function validNipProvider(): array
    {
        return [
            // Przykładowe prawidłowe NIP-y
            ['1234567890'], // Test case
            ['7821012356'], // Rzeczywisty przykład
            ['5261040828'], // Rzeczywisty przykład
            ['9511257934'], // Rzeczywisty przykład
        ];
    }

    /**
     * @dataProvider invalidNipProvider
     */
    public function testInvalidNips(string $nipValue, string $expectedMessage): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($expectedMessage);

        new Nip($nipValue);
    }

    public function invalidNipProvider(): array
    {
        return [
            ['123456789', 'NIP musi składać się z dokładnie 10 cyfr.'], // Za krótki
            ['12345678901', 'NIP musi składać się z dokładnie 10 cyfr.'], // Za długi
            ['123456789a', 'NIP może zawierać tylko cyfry.'], // Litery
            ['1234567899', 'Nieprawidłowa suma kontrolna NIP.'], // Nieprawidłowa suma kontrolna
            ['9999999999', 'Nieprawidłowa suma kontrolna NIP.'], // Nieprawidłowa suma kontrolna
        ];
    }

    public function testValueIsReadonly(): void
    {
        $nip = new Nip('1234567890');

        // Próba zmiany readonly property powinna zakończyć się błędem
        $this->expectError();
        $nip->value = '0987654321';
    }
}
