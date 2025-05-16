<?php

namespace App\Domain\Menu\Event;

class MenuRefreshScheduledEvent
{
    public function __construct(public int $refreshAtTimestamp)
    {
    }
}
