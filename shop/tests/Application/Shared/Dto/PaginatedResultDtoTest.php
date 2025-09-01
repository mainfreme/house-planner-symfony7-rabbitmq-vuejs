<?php

declare(strict_types = 1);

namespace App\Tests\Application\Shared\Dto;

use App\Shared\Application\Dto\PaginatedResultDto;
use PHPUnit\Framework\TestCase;

class PaginatedResultDtoTest extends TestCase
{
    public function testToApiArrayWithFirstPage(): void
    {
        $dto = new PaginatedResultDto(
            total: 50,
            page: 1,
            limit: 10,
            pages: 5,
            items: [
                ['id' => 1, 'name' => 'Test 1'],
                ['id' => 2, 'name' => 'Test 2'],
            ]
        );

        $expected = [
            'data' => [
                ['id' => 1, 'name' => 'Test 1'],
                ['id' => 2, 'name' => 'Test 2'],
            ],
            'meta' => [
                'total_item' => 50,
                'per_page' => 10,
                'total_page' => 1,
                'last_page' => 5,
            ],
            'links' => [
                'first' => '?page=1',
                'last' => '?page=5',
                'prev' => null,
                'next' => '?page=2',
            ]
        ];

        $this->assertSame($expected, $dto->toApiArray());
    }

    public function testToApiArrayWithLastPage(): void
    {
        $dto = new PaginatedResultDto(
            total: 100,
            page: 10,
            limit: 10,
            pages: 10,
            items: [['id' => 99], ['id' => 100]]
        );

        $response = $dto->toApiArray();

        $this->assertSame('?page=1', $response['links']['first']);
        $this->assertSame('?page=10', $response['links']['last']);
        $this->assertSame('?page=9', $response['links']['prev']);
        $this->assertNull($response['links']['next']);
    }

    public function testGetArray(): void
    {
        $items = [['id' => 1], ['id' => 2]];

        $dto = new PaginatedResultDto(
            total: 2,
            page: 1,
            limit: 10,
            pages: 1,
            items: $items
        );

        $array = $dto->getArray();

        $this->assertSame(2, $array['total']);
        $this->assertSame(1, $array['page']);
        $this->assertSame(10, $array['limit']);
        $this->assertSame(1, $array['pages']);
        $this->assertSame($items, $array['items']);
    }

}
