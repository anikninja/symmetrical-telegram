<?php

namespace App\Enums;

enum StatusEnum: int
{
    case ACTIVE = 1;
    case DEACTIVE = 0;

    public static function toArray(): array
    {
        return [
            self::ACTIVE->value => 'Active',
            self::DEACTIVE->value => 'Deactive',
        ];
    }
}
