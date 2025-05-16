<?php

namespace App\Infrastructure\Twig;

use App\Application\Menu\Service\MenuService;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

class MenuExtension extends AbstractExtension implements GlobalsInterface
{
    public function __construct(private MenuService $menuService) {}

    public function getGlobals(): array
    {
        return [
            'menuService' => $this->menuService,
        ];
    }
}
