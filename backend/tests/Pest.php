<?php

use App\Models\Monitor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

use function Jgss\LaravelPestScenarios\queryId;
use function Jgss\LaravelPestScenarios\queryModel;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class)
    ->in('Feature', 'Unit')
    ->beforeEach(function (): void {
        Bus::fake();
        Queue::fake();
        Mail::fake();

        config()->set('app.time_traveller_mode_enabled', false);
        config()->set('app.keep_access_token_in_cache', false);
        config()->set('mail.sending_enabled', true);
    });

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function queryMonitor(string $name): Monitor
{
    /** @var Monitor $monitor */
    $monitor = queryModel($name);

    return $monitor;
}

function queryUuid(string $name): string
{
    return queryMonitor($name)->uuid;
}

/**
 * @return Closure(): string
 */
function getQueryUuid(string $name): Closure
{
    return fn (): string => queryUuid($name);
}

/**
 * @return Closure(): string
 */
function getCachedToken(): Closure
{
    /** @phpstan-ignore-next-line  */
    return fn () => Cache::get('dev:last_monitor_token_'.queryId('monitor').'_'.getmypid());
}
