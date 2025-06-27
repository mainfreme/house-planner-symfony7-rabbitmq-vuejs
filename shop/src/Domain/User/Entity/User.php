<?php

namespace App\Domain\User\Entity;

use App\Infrastructure\Persistence\Doctrine\User\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use App\Domain\User\Enum\RoleEnum;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: "users")]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id', type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private string $email;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\Column(type: "string")]
    private string $password;

    #[Assert\NotBlank(groups: ['registration'])]
    private ?string $plainPassword = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    /**
     * Zwraca tablicę stringów (np. ['ROLE_USER', 'ROLE_ADMIN'])
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        if (!in_array(RoleEnum::USER->value, $roles)) {
            $roles[] = RoleEnum::USER->value;
        }

        return array_unique($roles);
    }

    /**
     * Ustawia role jako tablicę enumów lub stringów
     *
     * @param RoleEnum[]|string[] $roles
     */
    public function setRoles(array $roles): self
    {
        $this->roles = array_map(
            fn($role) => $role instanceof RoleEnum ? $role->value : (string) $role,
            $roles
        );

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;
        return $this;
    }

    public function eraseCredentials(): void
    {
        $this->plainPassword = null;
    }
}
