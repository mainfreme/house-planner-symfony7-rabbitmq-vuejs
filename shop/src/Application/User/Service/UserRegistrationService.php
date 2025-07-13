<?php

namespace App\Application\User\Service;

use App\Application\User\UI\Dto\UserRegisterDto;
use App\Domain\User\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserRegistrationService
{
    public function __construct(
        private readonly EntityManagerInterface      $em,
        private readonly UserPasswordHasherInterface $userPasswordHashed,
    )
    {
    }

    public function register(UserRegisterDto $dto): User
    {
        $user = new User();
        $user->setEmail($dto->email);
        $hashed = $this->userPasswordHashed->hashPassword($user, $dto->plainPassword);
        $user->setPassword($hashed);
        $this->em->persist($user);
        $this->em->flush();
        $user->eraseCredentials();

        return $user;
    }
}
