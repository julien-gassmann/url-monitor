<?php

declare(strict_types=1);

namespace Tests\Queries;

use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
}
