<?php

namespace App\Infrastructure\Menu\Consumer;

use App\Application\Menu\Service\MenuService;
use App\Domain\Menu\Event\MenuRefreshScheduledEvent;
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
