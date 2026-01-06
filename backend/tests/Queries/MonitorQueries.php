<?php

declare(strict_types=1);

namespace Tests\Queries;

use App\Models\Monitor;
use App\Models\MonitorCheck;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final readonly class MonitorQueries
{
    /**
     * @throws ModelNotFoundException
     */
    public static function getFirst(): Monitor
    {
        return Monitor::where('url', 'https://first.test')
            ->with('user')
            ->firstOrFail();
    }

    /**
     * @return Collection<int, MonitorCheck>
     */
    public static function getFirstChecks(): Collection
    {
        return MonitorCheck::whereRelation('monitor', 'url', 'https://first.test')->get();
    }

    /**
     * @throws ModelNotFoundException
     */
    public static function getOther(): Monitor
    {
        return Monitor::where('url', 'https://other.test')
            ->with('user')
            ->firstOrFail();
    }

    /**
     * @return Collection<int, MonitorCheck>
     */
    public static function getOtherChecks(): Collection
    {
        return MonitorCheck::whereRelation('monitor', 'url', 'https://other.test')->get();
    }
}
