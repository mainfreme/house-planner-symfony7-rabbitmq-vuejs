<?php

namespace App\Domain\User\Entity;

use App\Infrastructure\Persistence\Doctrine\User\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use App\Domain\User\Enum\RuleEnum;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: "users")]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid')]
    private UuidInterface $uuid;

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
        // For backward compatibility, return null since UUID can't be converted to int
        return null;
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function setUuid(UuidInterface $uuid): static
    {
        $this->uuid = $uuid;

        return $this;
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

        if (!in_array(RuleEnum::USER->value, $roles)) {
            $roles[] = RuleEnum::USER->value;
        }

        return array_unique($roles);
    }

    /**
     * Ustawia role jako tablicę enumów lub stringów
     *
     * @param RuleEnum[]|string[] $roles
     */
    public function setRoles(array $roles): self
    {
        $this->roles = array_map(
            fn($role) => $role instanceof RuleEnum ? $role->value : (string) $role,
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
