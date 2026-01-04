<?php

declare(strict_types=1);

namespace App\Enums;

enum PerPageEnum: int
{
    case ONE = 1;
    case FIVE = 5;
    case TEN = 10;
    case TWENTY_FIVE = 25;
    case FIFTY = 50;
    case ONE_HUNDRED = 100;
    case ALL = -1;

    public function label(): string
    {
        return $this === self::ALL ? 'Tous' : strval($this->value);
    }

    /**
     * @return array<int, array{
     *     label: string,
     *     value: int
     * }>
     */
    public static function forSelectDisplay(): array
    {
        return array_map(
            fn (PerPageEnum $case): array => [
                'label' => $case->label(),
                'value' => $case->value,
            ],
            self::cases()
        );
    }
}
