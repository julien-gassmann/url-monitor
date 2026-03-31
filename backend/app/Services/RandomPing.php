<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\HttpCodeEnum;
use Illuminate\Support\Sleep;

class RandomPing
{
    public function pong(int $probability): int
    {
        /** @var int $result */
        $result = match (true) {
            $probability > 20 => 200,
            $probability > 5 => HttpCodeEnum::random()->value,
            default => Sleep::for(10)->seconds()->then(fn (): int => 0),
        };

        return $result;
    }
}
