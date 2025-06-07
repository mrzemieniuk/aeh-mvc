<?php

namespace App\Enum;

enum AlertLevelEnum: string
{
    case INFO = 'informacyjny';
    case WARNING = 'ostrzegający';
    case CRITICAL = 'krytyczny';

    public static function choices(): array
    {
        return array_column(self::cases(), 'name', 'value');
    }
}
