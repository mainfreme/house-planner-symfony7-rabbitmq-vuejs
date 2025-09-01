<?php

declare(strict_types=1);

namespace App\Shared\Application\Dto;

final class ResponseDto implements ResponseDtoInterface
{

    public function __construct(
        public readonly array $item,
    )
    {
    }

    public function getArray(): array
    {
        return get_object_vars($this);
    }

    public function toApiArray(): array
    {
        return [
            'data' => $this->item
        ];
    }
}
