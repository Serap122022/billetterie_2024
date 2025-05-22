<?php

namespace App\Enum;

enum PaymentStatusEnum: string
{
    case PENDING = 'pending';     // En attente
    case COMPLETED = 'completed';  // Complété
    case FAILED = 'failed';        // Échoué
    case SUCCESS = 'success';      // Réussi

    public static function getChoices(): array
    {
        return [
            self::PENDING->value => 'En attente',
            self::COMPLETED->value => 'Complété',
            self::FAILED->value => 'Échoué',
            self::SUCCESS->value => 'Réussi',
        ];
    }
}
