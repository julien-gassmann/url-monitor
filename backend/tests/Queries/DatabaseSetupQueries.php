<?php

declare(strict_types=1);

namespace Tests\Queries;

use App\Models\Monitor;
use App\Models\MonitorAccessToken;
use App\Models\MonitorCheck;
use App\Models\User;
use App\Services\AccessTokenVerifier;

final readonly class DatabaseSetupQueries
{
    // -------------------------- Monitors --------------------------

    public static function createUser(): User
    {
        /** @var User $user */
        $user = User::factory()->create();

        return $user;
    }

    public static function createMonitor(): Monitor
    {
        return DatabaseSetupQueries::createUser()
            ->monitors()
            ->create(Monitor::factory()->make(['url' => 'https://first.test'])->toArray());
    }

    public static function createOtherMonitor(): Monitor
    {
        return DatabaseSetupQueries::createUser()
            ->monitors()
            ->create(Monitor::factory()->make(['url' => 'https://other.test'])->toArray());
    }

    public static function createManyMonitors(): void
    {
        DatabaseSetupQueries::createUser()
            ->monitors()
            ->createMany(Monitor::factory(10)->make()->toArray());
    }

    // -------------------------- Monitor Checks --------------------------

    public static function createChecks(): void
    {
        DatabaseSetupQueries::createMonitor()
            ->checks()
            ->createMany(MonitorCheck::factory(10)->make()->toArray());
    }

    public static function createOtherChecks(): void
    {
        DatabaseSetupQueries::createOtherMonitor()
            ->checks()
            ->createMany(MonitorCheck::factory(10)->make()->toArray());
    }

    // -------------------------- Monitor Access Tokens --------------------------

    /**
     * @param  array<string, mixed>  $attributes
     */
    private static function createMonitorAccessToken(array $attributes = []): void
    {
        $monitor = Monitor::first() ?? DatabaseSetupQueries::createMonitor();
        $token = AccessTokenVerifier::generate($monitor->id);
        $monitor->accessTokens()->create(
            MonitorAccessToken::factory()->make([
                'token_hash' => AccessTokenVerifier::hash($token),
                ...$attributes,
            ])->toArray()
        );
    }

    public static function createAccessToken(): void
    {
        self::createMonitorAccessToken(['used_at' => null]);
    }

    public static function createManyAccessTokens(): void
    {
        for ($i = 0; $i < 5; $i++) {
            self::createMonitorAccessToken(['used_at' => null]);
        }
    }

    public static function createUsedAccessToken(): void
    {
        self::createMonitorAccessToken(['used_at' => now()]);
    }

    public static function createRefreshedAccessToken(): void
    {
        self::createMonitorAccessToken(['used_at' => now(), 'refreshed_at' => now()]);
    }

    public static function createExpiredAccessToken(): void
    {
        self::createMonitorAccessToken(['expires_at' => now()->subMinute()]);
    }
}
