<?php

declare(strict_types=1);

namespace App\Tests\Client\Application\UI\Http\Controller\Api;

use App\Client\Application\Dto\CompanyByNipResponse;
use App\Client\Application\Query\GetCompanyByNipQuery;
use App\Client\Application\Query\Handler\GetCompanyByNipHandler;
use App\Client\Application\UI\Http\Controller\Api\ApiCompanyController;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiCompanyControllerTest extends TestCase
{
    private GetCompanyByNipHandler $handler;
    private SerializerInterface $serializer;
    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        $this->handler = $this->createMock(GetCompanyByNipHandler::class);
        $this->serializer = $this->createMock(SerializerInterface::class);
        $this->validator = $this->createMock(ValidatorInterface::class);
    }

    public function testGetCompanyByNipReturnsCompanyData(): void
    {
        // Arrange
        $nip = '1234567890';
        $request = new Request([], [], [], [], [], [], json_encode(['nip' => $nip]));

        $responseDto = new CompanyByNipResponse([
            'Nazwa' => 'Testowa Firma Sp. z o.o.',
            'Nip' => $nip,
            'Regon' => '012345678',
            'Ulica' => 'Testowa',
            'NrNieruchomosci' => '10',
            'NrLokalu' => '15',
            'KodPocztowy' => '00-001',
            'Miejscowosc' => 'Warszawa',
        ]);

        // Mock validator - brak błędów
        $this->validator->expects($this->once())
            ->method('validate')
            ->willReturn(new ConstraintViolationList());

        // Mock handler
        $this->handler->expects($this->once())
            ->method('handle')
            ->with($this->callback(function ($query) use ($nip) {
                return $query instanceof GetCompanyByNipQuery && $query->nip === $nip;
            }))
            ->willReturn($responseDto);

        $controller = new ApiCompanyController(
            $this->handler,
            $this->serializer,
            $this->validator
        );

        // Act
        $response = $controller->getCompanyByNip($request);

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('Testowa Firma Sp. z o.o.', $responseData['name']);
        $this->assertEquals($nip, $responseData['nip']);
        $this->assertEquals('012345678', $responseData['regon']);
    }

    public function testGetCompanyByNipReturns404WhenCompanyNotFound(): void
    {
        // Arrange
        $nip = '9999999999';
        $request = new Request([], [], [], [], [], [], json_encode(['nip' => $nip]));

        $responseDto = new CompanyByNipResponse(); // Puste dane

        // Mock validator - brak błędów
        $this->validator->expects($this->once())
            ->method('validate')
            ->willReturn(new ConstraintViolationList());

        // Mock handler - zwraca pustą odpowiedź
        $this->handler->expects($this->once())
            ->method('handle')
            ->willReturn($responseDto);

        $controller = new ApiCompanyController(
            $this->handler,
            $this->serializer,
            $this->validator
        );

        // Act
        $response = $controller->getCompanyByNip($request);

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());

        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertStringContainsString('Nie znaleziono firmy', $responseData['message']);
    }

    public function testGetCompanyByNipReturns400WhenNipMissing(): void
    {
        // Arrange
        $request = new Request([], [], [], [], [], [], json_encode([]));

        $controller = new ApiCompanyController(
            $this->handler,
            $this->serializer,
            $this->validator
        );

        // Act
        $response = $controller->getCompanyByNip($request);

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());

        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('error', $responseData);
        $this->assertStringContainsString('NIP jest wymagany', $responseData['error']);
    }

    public function testGetCompanyByNipReturns400WhenNipEmpty(): void
    {
        // Arrange
        $request = new Request([], [], [], [], [], [], json_encode(['nip' => '']));

        $controller = new ApiCompanyController(
            $this->handler,
            $this->serializer,
            $this->validator
        );

        // Act
        $response = $controller->getCompanyByNip($request);

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function testGetCompanyByNipReturns400WhenValidationFails(): void
    {
        // Arrange
        $nip = '123'; // Nieprawidłowy NIP
        $request = new Request([], [], [], [], [], [], json_encode(['nip' => $nip]));

        // Mock validator - zwraca błędy walidacji
        $violation = $this->createMock(ConstraintViolation::class);
        $violation->method('getPropertyPath')->willReturn('nip');
        $violation->method('getMessage')->willReturn('NIP musi składać się z dokładnie 10 cyfr');

        $violations = new ConstraintViolationList([$violation]);

        $this->validator->expects($this->once())
            ->method('validate')
            ->willReturn($violations);

        $controller = new ApiCompanyController(
            $this->handler,
            $this->serializer,
            $this->validator
        );

        // Act
        $response = $controller->getCompanyByNip($request);

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());

        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('errors', $responseData);
        $this->assertArrayHasKey('nip', $responseData['errors']);
    }

    public function testGetCompanyByNipReturns500OnHandlerException(): void
    {
        // Arrange
        $nip = '1234567890';
        $request = new Request([], [], [], [], [], [], json_encode(['nip' => $nip]));

        // Mock validator - brak błędów
        $this->validator->expects($this->once())
            ->method('validate')
            ->willReturn(new ConstraintViolationList());

        // Mock handler - rzuca wyjątek
        $this->handler->expects($this->once())
            ->method('handle')
            ->willThrowException(new \RuntimeException('Błąd połączenia z GUS'));

        $controller = new ApiCompanyController(
            $this->handler,
            $this->serializer,
            $this->validator
        );

        // Act
        $response = $controller->getCompanyByNip($request);

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $response->getStatusCode());

        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('error', $responseData);
        $this->assertStringContainsString('Błąd połączenia z GUS', $responseData['error']);
    }

    public function testGetCompanyByNipHandlesInvalidJson(): void
    {
        // Arrange
        $request = new Request([], [], [], [], [], [], 'invalid json');

        $controller = new ApiCompanyController(
            $this->handler,
            $this->serializer,
            $this->validator
        );

        // Act
        $response = $controller->getCompanyByNip($request);

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
}

