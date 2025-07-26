<?php

declare(strict_types=1);

namespace App\Menu\Application\Handler;

use App\Menu\Application\Command\ScheduleMenuRefreshCommand;
use App\Menu\Domain\Event\MenuRefreshScheduledEvent;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
class ScheduleMenuRefreshHandler
{
    public function __construct(private MessageBusInterface $eventBus) {}

    /**
     * @throws ExceptionInterface
     */
    public function __invoke(ScheduleMenuRefreshCommand $command)
    {
        $event = new MenuRefreshScheduledEvent($command->refreshAtTimestamp);
        $this->eventBus->dispatch($event);
    }
}
