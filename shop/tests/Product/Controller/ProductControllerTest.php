<?php

namespace App\Tests\Product\Controller;


use App\Application\Product\Dto\PriceRangeDto;
use App\Application\Product\Dto\ProductFilterDto;
use App\Application\Product\UI\Http\Controller\Api\ApiProductController;
use App\Repository\ProductRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiProductControllerTest extends TestCase
{
    public function testGetListReturnsExpectedJsonResponse(): void
    {
        // Arrange
        $productRepository = $this->createMock(ProductRepository::class);
        $serializer = $this->createMock(SerializerInterface::class);
        $validator = $this->createMock(ValidatorInterface::class);

        // Mock danych wejściowych
        $queryParams = ['category' => 'shoes', 'page' => 1];
        $request = new Request($queryParams);

        // Mock ProductFilterDto
        $filterDto = $this->createMock(ProductFilterDto::class);
        $filterDto->method('toArray')->willReturn($queryParams);
        $filterDto->method('getPage')->willReturn(1);

        // Mock walidacji — brak błędów
        $validator->expects($this->once())
            ->method('validate')
            ->with($filterDto)
            ->willReturn(new ConstraintViolationList());

        // Mock serializer::denormalize
        $serializer->expects($this->once())
            ->method('denormalize')
            ->with($queryParams, ProductFilterDto::class)
            ->willReturn($filterDto);

        // Mock zwróconych produktów
        $productList = [
            ['id' => 1, 'name' => 'Sneakers'],
            ['id' => 2, 'name' => 'Boots'],
        ];

        $productRepository->expects($this->once())
            ->method('findByCriteria')
            ->with($queryParams)
            ->willReturn($productList);

        // Mock serializer::serialize do JSON
        $serializer->expects($this->once())
            ->method('serialize')
            ->with($productList, 'json')
            ->willReturn(json_encode($productList));

        // System Under Test
        $controller = new ApiProductController($productRepository, $serializer);

        // Act
        $response = $controller->getList($request, $validator);

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        $expectedData = [
            'page' => 1,
            'items' => $productList,
            'total_pages' => 3
        ];
        $this->assertJsonStringEqualsJsonString(json_encode($expectedData), $response->getContent());
    }

    public function testGetRangePriceReturnsExpectedJson(): void
    {
        // Arrange
        $productRepository = $this->createMock(ProductRepository::class);
        $serializer = $this->createMock(SerializerInterface::class);

        $category = 'electronics';
        $priceRange = ['min' => 99.99, 'max' => 1999.99];

        // Expectacja: repozytorium zwraca zakres cen
        $productRepository->expects($this->once())
            ->method('findMinMaxPrice')
            ->with($category)
            ->willReturn($priceRange);

        // Mock Dto
        $priceRangeDto = $this->createMock(PriceRangeDto::class);
        $priceRangeDto->expects($this->once())
            ->method('toArray')
            ->willReturn(['min' => 99.99, 'max' => 1999.99]);

        // Expectacja: serializer zwraca Dto
        $serializer->expects($this->once())
            ->method('denormalize')
            ->with($priceRange, PriceRangeDto::class)
            ->willReturn($priceRangeDto);

        // SUT
        $controller = new ApiProductController($productRepository, $serializer);

        // Act
        $response = $controller->getRangePrice($category);

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        $expectedJson = json_encode(['min' => 99.99, 'max' => 1999.99]);
        $this->assertJsonStringEqualsJsonString($expectedJson, $response->getContent());
    }
}
