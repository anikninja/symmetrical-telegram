<?php

namespace App\Enums;

enum ExportEnum: int
{
    case PDF = 1;
    case TXT = 2;
    case MD = 3;

    public static function toArray(): array
    {
        return [
            self::PDF->value => 'PDF',
            self::TXT->value => 'TXT',
            self::MD->value => 'MD',
        ];
    }

    public static function toValues(): array
    {
        return [
            self::PDF->value,
            self::TXT->value,
            self::MD->value,
        ];
    }
}
