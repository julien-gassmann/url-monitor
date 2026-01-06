<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\HttpCodeEnum;

class RandomPing
{
    public function pong(int $probability): int
    {
        return match (true) {
            $probability > 20 => 200,
            $probability > 5 => HttpCodeEnum::random()->value,
            default => sleep(10),
        };
    }
}
