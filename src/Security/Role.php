<?php

namespace App\Security;

final class Role
{
    const USER = 'ROLE_USER';
    const ADMIN = 'ROLE_ADMIN';
    const SUPER_ADMIN = 'ROLE_SUPER_ADMIN';

    const TITLE_USER = 'Utilisateur';
    const TITLE_ADMIN = 'Administrateur';
    const TITLE_SUPER_ADMIN = 'Super administrateur';

    const TITLES = [
        self::USER => self::TITLE_USER,
        self::ADMIN => self::TITLE_ADMIN,
        self::SUPER_ADMIN => self::TITLE_SUPER_ADMIN,
    ];

    public static function getRoles(): array
    {
        return [self::USER, self::ADMIN];
    }

    public static function getTitleByRole(string $role): ?string
    {
        if (!isset(self::TITLES[$role])) {
            return null;
        }

        return self::TITLES[$role];
    }
}
