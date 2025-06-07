<?php

namespace App\Enum;

enum EmergencyAlertStatusEnum: string
{
    case DRAFT = 'szkic';
    case ACTIVE = 'opublikowany i aktywny';
    case RESOLVED = 'opublikowany i opanowany';
    case CANCELLED = 'opublikowany i anulowany';
    case ARCHIVED = 'zarchiwizowany';

    public static function choices(): array
    {
        return array_column(self::cases(), 'name', 'value');
    }
}
