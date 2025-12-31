<?php

declare(strict_types=1);

namespace App\Enums;

enum StatusEnum: string
{
    case UP = 'up';
    case DOWN = 'down';
    case UNREACHABLE = 'unreachable';

    public function label(): string
    {
        return $this->name;
    }

    public static function fromHttpCode(?int $code, int $expectedCode): self
    {
        return match ($code) {
            $expectedCode => StatusEnum::UP,
            null => StatusEnum::UNREACHABLE,
            default => StatusEnum::DOWN,
        };
    }
}
