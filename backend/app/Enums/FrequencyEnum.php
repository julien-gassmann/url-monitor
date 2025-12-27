<?php

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
     * @return array<int, string>
     */
    public static function forSelectDisplay(): array
    {
        return array_map(
            fn (FrequencyEnum $case): string => $case->label(),
            self::cases()
        );
    }
}
