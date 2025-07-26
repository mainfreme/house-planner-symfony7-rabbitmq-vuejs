<?php

declare(strict_types=1);

namespace App\Menu\Infrastructure\Consumer;

use App\Menu\Application\Service\MenuService;
use App\Menu\Domain\Event\MenuRefreshScheduledEvent;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class RefreshMenuConsumer
{
    public function __construct(private MenuService $menuCacheService) {}

    public function __invoke(MenuRefreshScheduledEvent $event)
    {
        if (time() >= $event->refreshAtTimestamp) {
            $this->menuCacheService->refreshCache();
        }
    }
}
