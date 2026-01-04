<?php

declare(strict_types=1);

namespace App\Enums;

enum MetadataPageEnum: string
{
    case CREATE_MONITOR = 'create-monitor';
    case PAGINATE_CHECKS = 'paginate-checks';
}
