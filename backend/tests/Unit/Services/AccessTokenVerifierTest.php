<?php

use App\Enums\InvalidReasonEnum;
use App\Models\Monitor;
use App\Models\MonitorAccessToken;
use App\Services\AccessTokenVerifier;

use function Jgss\LaravelPestScenarios\databaseSetup;

/**
 * ───────────────────────────────────────
 * Valid scenarios for Action AccessTokenVerifier
 * ───────────────────────────────────────
 */
describe('Services - AccessTokenVerifier : success', function (): void {
    describe('generate', function (): void {
        it('generates a non-empty token', function (): void {
            // Act: Generate token
            $token = AccessTokenVerifier::generate();

            // Assert: Token is created
            expect($token)
                ->toBeString()
                ->not()->toBeEmpty();
        });

        it('generates a 64-char hex token', function (): void {
            // Act: Generate token
            $token = AccessTokenVerifier::generate();

            // Assert: Token is valid
            expect(strlen($token))->toBe(64)
                ->and(ctype_xdigit($token))->toBeTrue();
        });

        it('generates unique tokens', function (): void {
            // Act: Generate a pair of tokens
            $tokenA = AccessTokenVerifier::generate();
            $tokenB = AccessTokenVerifier::generate();

            // Assert: Tokens are different
            expect($tokenA)->not()->toBe($tokenB);
        });
    });

    describe('hash', function (): void {
        it('hashes a token using sha256', function (): void {
            // Act: Hash Token
            $token = 'plain-text-token';
            $hash = AccessTokenVerifier::hash($token);

            // Assert: Hash is valid
            expect($hash)
                ->toBeString()
                ->toHaveLength(64)
                ->toBe(hash('sha256', $token));
        });

        it('always returns the same hash for the same token', function (): void {
            // Arrange: Declare token
            $token = 'same-token';

            // Assert: Hash are the same
            expect(AccessTokenVerifier::hash($token))
                ->toBe(AccessTokenVerifier::hash($token));
        });
    });

    describe('verify', function (): void {
        it('fails if token is already used', function (): void {
            // Arrange: Create used token
            databaseSetup('create_used_token');
            $token = MonitorAccessToken::firstOrFail();

            // Act: Verify token
            $result = AccessTokenVerifier::verify($token);

            // Assert: Result has corresponding invalid reason and no monitor
            expect($result->isValid)->toBeFalse()
                ->and($result->reason)->toBe(InvalidReasonEnum::AlreadyUsed)
                ->and($result->monitor)->toBeNull();
        });

        it('fails if token is expired', function (): void {
            // Arrange: Create expired token
            databaseSetup('create_expired_token');
            $token = MonitorAccessToken::firstOrFail();

            // Act: Verify token
            $result = AccessTokenVerifier::verify($token);

            // Assert: Result has corresponding invalid reason and no monitor
            expect($result->isValid)->toBeFalse()
                ->and($result->reason)->toBe(InvalidReasonEnum::Expired)
                ->and($result->monitor)->toBeNull();
        });

        it('succeeds if token is valid', function (): void {
            // Arrange: Create valid token
            databaseSetup('create_token');
            $monitor = Monitor::firstOrFail();
            $token = MonitorAccessToken::firstOrFail();

            // Act: Verify token
            $result = AccessTokenVerifier::verify($token);

            // Assert: Result has corresponding monitor and no invalid reason
            expect($result->isValid)->toBeTrue()
                ->and($result->reason)->toBeNull()
                ->and($result->monitor)->toBeInstanceOf(Monitor::class)
                ->and($result->monitor?->id)->toBe($monitor->id);
        });
    });
});
