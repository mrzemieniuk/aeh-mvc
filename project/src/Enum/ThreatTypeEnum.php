<?php

namespace App\Enum;

enum ThreatTypeEnum: string
{
    case NATURAL = 'rozległe zagrożenie naturalne';
    case TECHNOLOGICAL = 'rozległe zagrożenie technologiczne';
    case BIOLOGICAL = 'rozległe zagrożenie biologiczne i zdrowotne';
    case SOCIAL = 'poważne zagrożenie społeczne i kryminalne';
    case LOCAL = 'drobne zagrożenie społeczne i kryminalne';
    case OTHER = 'inne';

    public static function choices(): array
    {
        return array_column(self::cases(), 'name', 'value');
    }
}
