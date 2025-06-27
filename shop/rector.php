<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Symfony\Set\SymfonySetList;

return static function (RectorConfig $rectorConfig): void {
    // Ścieżki do refaktoryzacji
    $rectorConfig->paths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ]);

    // Załaduj zestawy dla Symfony
    $rectorConfig->sets([
        SymfonySetList::SYMFONY_70,
    ]);
};
