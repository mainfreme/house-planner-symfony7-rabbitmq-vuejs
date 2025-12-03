<?php

declare(strict_types=1);

namespace App\Tests\Client\Application\Query;

use App\Client\Application\Query\GetCompanyByNipQuery;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class GetCompanyByNipQueryTest extends TestCase
{
    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        $this->validator = Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->getValidator();
    }

    public function testValidNip(): void
    {
        // Arrange
        $nip = '1234567890';
        $query = new GetCompanyByNipQuery($nip);

        // Act
        $violations = $this->validator->validate($query);

        // Assert
        $this->assertCount(0, $violations);
        $this->assertEquals($nip, $query->nip);
    }

    public function testEmptyNip(): void
    {
        // Arrange
        $query = new GetCompanyByNipQuery('');

        // Act
        $violations = $this->validator->validate($query);

        // Assert
        $this->assertCount(1, $violations);
        $this->assertEquals('NIP nie może być pusty', $violations[0]->getMessage());
    }

    public function testNipTooShort(): void
    {
        // Arrange
        $query = new GetCompanyByNipQuery('123456789');

        // Act
        $violations = $this->validator->validate($query);

        // Assert
        $this->assertGreaterThan(0, $violations->count());
        $violationMessages = [];
        foreach ($violations as $violation) {
            $violationMessages[] = $violation->getMessage();
        }
        $this->assertContains('NIP musi składać się z dokładnie 10 cyfr', $violationMessages);
    }

    public function testNipTooLong(): void
    {
        // Arrange
        $query = new GetCompanyByNipQuery('12345678901');

        // Act
        $violations = $this->validator->validate($query);

        // Assert
        $this->assertGreaterThan(0, $violations->count());
    }

    public function testNipWithLetters(): void
    {
        // Arrange
        $query = new GetCompanyByNipQuery('123456789a');

        // Act
        $violations = $this->validator->validate($query);

        // Assert
        $this->assertGreaterThan(0, $violations->count());
        $violationMessages = [];
        foreach ($violations as $violation) {
            $violationMessages[] = $violation->getMessage();
        }
        $this->assertContains('NIP może zawierać tylko cyfry', $violationMessages);
    }

    public function testNipWithSpecialCharacters(): void
    {
        // Arrange
        $query = new GetCompanyByNipQuery('123456-789');

        // Act
        $violations = $this->validator->validate($query);

        // Assert
        $this->assertGreaterThan(0, $violations->count());
    }
}

