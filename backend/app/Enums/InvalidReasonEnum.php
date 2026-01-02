<?php

declare(strict_types=1);

namespace App\Enums;

enum InvalidReasonEnum: string
{
    case AlreadyUsed = 'already_used';
    case Expired = 'expired';
    case NotFound = 'not_found';

    public function label(): string
    {
        return match ($this->value) {
            'already_used' => 'Le lien de vérification a déjà été utilisé.',
            'expired' => 'Le lien de vérification est arrivé à expiration. ',
            'not_found' => 'Le lien de vérification n\'est pas reconnue. ',
        };
    }
}
