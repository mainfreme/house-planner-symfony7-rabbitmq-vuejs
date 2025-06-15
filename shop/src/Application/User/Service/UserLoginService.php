<?php

namespace App\Application\User\Service;

use App\Domain\User\Entity\User;
use App\Infrastructure\Persistence\Doctrine\Client\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use App\Application\User\Event\UserLoggedInEvent;

class LoginService
{
    public function __construct(
        private readonly UserRepository              $userRepository,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly EventDispatcherInterface    $eventDispatcher
    )
    {
    }

    /**
     * @param string $email
     * @param string $plainPassword
     * @return User
     */
    public function login(string $email, string $plainPassword): User
    {
        $user = $this->userRepository->findOneBy(['email' => $email]);

        if (!$user || !$this->passwordHasher->isPasswordValid($user, $plainPassword)) {
            throw new \InvalidArgumentException('Invalid credentials.');
        }

        $this->eventDispatcher->dispatch(new UserLoggedInEvent($user));

        return $user;
    }
}
