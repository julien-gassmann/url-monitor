<?php

use App\Enums\HttpCodeEnum;
use App\Services\RandomPing;
use Carbon\CarbonInterval;
use Illuminate\Support\Sleep;

/**
 * ───────────────────────────────────────
 * Valid scenarios for Service RandomPing
 * ───────────────────────────────────────
 */
describe('Services - RandomPing : success', function (): void {

    it('returns 200 when probability is greater than 20', function (): void {
        // Act: Get result
        $response = (new RandomPing)->pong(50);

        // Assert: HTTP code is 200
        expect($response)->toBe(200);
    });

    it('returns a valid random HTTP code when probability is between 6 and 20', function (): void {
        // Arrange: Get allowed HTTP status codes
        $allowedCodes = collect(HttpCodeEnum::cases())
            ->pluck('value')
            ->all();

        // Act: Get result
        $response = (new RandomPing)->pong(10);

        // Assert: HTTP code is allowed
        expect($response)
            ->toBeInt()
            ->toBeIn($allowedCodes);
    });

    it('simulates an unreachable service when probability is 5 or lower', function (): void {
        // Act: Get result
        $response = (new RandomPing)->pong(1);

        // Assert: slept exactly 10 seconds
        Sleep::assertSlept(fn (CarbonInterval $sleep): bool => $sleep->seconds === 10);

        // Assert: HTTP code is 0 (invalid)
        expect($response)->toBe(0);
    });
});
