<?php

namespace App\Application\Menu\Command;


class ScheduleMenuRefreshCommand
{
    public function __construct(public int $refreshAtTimestamp)
    {
    }
}
