<?php

declare(strict_types=1);

namespace App\Menu\Application\Command;


class ScheduleMenuRefreshCommand
{
    public function __construct(public int $refreshAtTimestamp)
    {
    }
}
