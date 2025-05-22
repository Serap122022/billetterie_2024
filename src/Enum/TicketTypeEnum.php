<?php

namespace App\Enum;

enum TicketTypeEnum: string
{
    case SOLO = 'Solo';
    case DUO = 'Duo';
    case FAMILY = 'Family';
    case OTHER = 'Autres';

    public static function getChoices(): array
    {
        return [
            'Solo' => self::SOLO->value,
            'Duo' => self::DUO->value,
            'Family' => self::FAMILY->value,
            'Autres types' => self::OTHER->value,
        ];
    }

    public static function getDescription(string $type): string
    {
        return match ($type) {
            self::SOLO ->value => 'Solo (1 personne)',
            self::DUO->value => 'Duo (2 personnes)',
            self::FAMILY->value => 'Familiale (4 personnes)',
            default => 'Autres types',
        };
    }
}
