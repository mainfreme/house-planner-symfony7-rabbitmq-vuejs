<?php

declare(strict_types=1);

namespace App\Tests\Application\Shared\Dto;

use App\Application\Shared\Dto\ResponseDto;
use PHPUnit\Framework\TestCase;

class ResponseDtoTest extends TestCase
{
    public function testToApiArray(): void
    {
        $item = ['id' => 99];
        $dto = new ResponseDto(
            item: $item
        );

        $response = $dto->toApiArray();

        $this->assertSame($item, $response['data']);
    }

    public function testGetArray(): void
    {
        $item = ['id' => 1];

        $dto = new ResponseDto(
            item: $item
        );

        $array = $dto->getArray();
        $this->assertSame($item, $array['item']);
    }
}
