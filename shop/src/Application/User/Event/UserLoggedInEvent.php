<?php

namespace App\Application\User\Event;

use App\Domain\User\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

class UserLoggedInEvent extends Event
{
    public function __construct(
        public readonly User $user
    ) {}
}
