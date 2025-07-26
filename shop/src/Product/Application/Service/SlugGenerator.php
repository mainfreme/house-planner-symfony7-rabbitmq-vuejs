<?php

declare(strict_types=1);

namespace App\Product\Application\Service;

class SlugGenerator
{

    public static function generateSlug(string $name): string
    {
        return ltrim(rtrim(str_replace(' ', '-', $name)));
    }
}
