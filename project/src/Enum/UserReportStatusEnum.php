<?php

namespace App\Enum;

enum UserReportStatusEnum: string
{
    case PENDING = 'wysłane, czeka na weryfikację';
    case IN_REVIEW = 'sprawdzane przez służby';
    case REJECTED = 'odrzucone';
    case ESCALATED = 'przekazane do odpowiednich służb';
    case RESOLVED = 'obsłużone';
    case CANCELLED = 'anulowane';

    public static function choices(): array
    {
        return array_column(self::cases(), 'name', 'value');
    }
}
