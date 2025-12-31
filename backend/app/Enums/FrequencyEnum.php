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
        $frequencies = [];

        foreach (self::cases() as $case) {
            $frequencies[] = ['label' => $case->label()];
        }

        return $frequencies;
    }
}
