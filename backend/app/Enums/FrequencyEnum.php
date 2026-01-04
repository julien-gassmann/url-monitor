<?php

declare(strict_types=1);

namespace App\Enums;

enum FrequencyEnum: string
{
    case DAILY = 'daily';
    case WEEKLY = 'weekly';
    case MONTHLY = 'monthly';

    public function label(): string
    {
        return ucfirst($this->value);
    }

    /**
     * @return array<int, array{label: string}>
     */
    public static function forSelectDisplay(): array
    {
        return array_map(
            fn (FrequencyEnum $case): array => ['label' => $case->label()],
            self::cases()
        );
    }
}
