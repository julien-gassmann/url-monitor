<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Throwable;

final readonly class UrlCheckService
{
    public function check(string $url): ?int
    {
        try {
            return Http::timeout(5)
                ->get($url)
                ->status();
        } catch (Throwable) {
            return null;
        }
    }
}
