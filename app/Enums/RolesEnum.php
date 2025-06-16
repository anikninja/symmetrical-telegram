<?php

namespace App\Enums;

enum RolesEnum: string
{
    case ADMIN = 'admin';
    case USER = 'user';

    public static function toArray(): array
    {
        return [
            self::ADMIN->value => 'Admin',
            self::USER->value => 'User',
        ];
    }
}
