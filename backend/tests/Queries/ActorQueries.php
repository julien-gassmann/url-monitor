<?php

declare(strict_types=1);

namespace Tests\Queries;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Sanctum\Sanctum;

final readonly class ActorQueries
{
    /**
     * @throws ModelNotFoundException
     */
    public static function user(): User
    {
        return User::whereRelation('monitors', 'url', 'https://first.test')
            ->firstOrFail();
    }

    /**
     * @throws ModelNotFoundException
     */
    public static function other(): User
    {
        return User::whereRelation('monitors', 'url', 'https://other.test')
            ->firstOrFail();
    }

    public static function sanctum(): Authenticatable
    {
        return Sanctum::actingAs(
            queryMonitor('monitor')->user,
            ['view-monitor:'.queryMonitor('monitor')->id]
        );
    }
}
