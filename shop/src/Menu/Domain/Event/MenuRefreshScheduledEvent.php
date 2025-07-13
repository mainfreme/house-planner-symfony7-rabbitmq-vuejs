<?php

declare(strict_types=1);

namespace App\Menu\Domain\Event;

class MenuRefreshScheduledEvent
{
    public function __construct(public int $refreshAtTimestamp)
    {
    }
}
