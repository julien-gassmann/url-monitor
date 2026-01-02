<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Monitor;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use InvalidArgumentException;

final readonly class DevTokenHelper
{
    /**
     * @throws InvalidArgumentException
     */
    public static function isEnabled(): bool
    {
        return config()->boolean('app.keep_access_token_in_cache');
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function putInCache(?int $monitorId, string $token): void
    {
        if ($monitorId && DevTokenHelper::isEnabled()) {
            Cache::put("dev:last_monitor_token_$monitorId", $token, now()->addMinute());
        }
    }

    /**
     * @throws InvalidArgumentException|ModelNotFoundException
     */
    public static function response(): JsonResponse
    {
        if (! DevTokenHelper::isEnabled()) {
            return response()->json(status: 201);
        }

        sleep(1); // Wait for job to be done
        $id = Monitor::latest()->firstOrFail()->id;
        $token = Cache::get("dev:last_monitor_token_$id");

        return response()->json(data: ['token' => $token], status: 201);
    }
}
