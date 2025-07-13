<?php

namespace App\Domain\User\Enum;

enum RuleEnum: string
{
    case USER = 'ROLE_USER';
    case ADMIN = 'ROLE_ADMIN';
    case MODERATOR = 'ROLE_MODERATOR';

    public static function fromStrings(array $roles): array
    {
        return array_map(fn($role) => RuleEnum::from($role), $roles);
    }

    public static function toStrings(array $enums): array
    {
        return array_map(fn($role) => $role->value, $enums);
    }
}
