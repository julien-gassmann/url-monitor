<?php

declare(strict_types=1);

namespace App\Enums;

enum InvalidReasonEnum: string
{
    case AlreadyUsed = 'already_used';
    case Expired = 'expired';
}
