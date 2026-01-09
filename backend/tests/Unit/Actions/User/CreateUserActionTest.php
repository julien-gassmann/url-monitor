<?php

use App\Actions\User\CreateUserAction;
use App\Models\User;
use Carbon\Carbon;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;

beforeEach(function (): void {
    Carbon::setTestNow(Carbon::parse('2026-01-01 12:00:00'));
});

afterEach(function (): void {
    Carbon::setTestNow();
});

/**
 * ───────────────────────────────────────
 * Valid scenarios for Action CreateUserAction
 * ───────────────────────────────────────
 */
describe('Actions - CreateUserAction : success', function (): void {
    it('creates user when mail does not exist', function (): void {
        // Act: Call action's handle method
        /** @noinspection PhpUnhandledExceptionInspection */
        $user = app(CreateUserAction::class)->handle('new@example.com');

        // Assert: User is returned
        expect($user)->toBeInstanceOf(User::class)
            ->and($user->email)->toBe('new@example.com');

        // Assert: User has been created
        assertDatabaseHas('users', [
            'email' => 'new@example.com',
        ]);
    });

    it('retrieves user when mail already exists', function (): void {
        // Arrange: Create user
        User::factory()->create(['email' => 'existing@example.com']);

        // Act: Call action's handle method
        /** @noinspection PhpUnhandledExceptionInspection */
        $user = app(CreateUserAction::class)->handle('existing@example.com');

        // Assert: User is returned
        expect($user)->toBeInstanceOf(User::class)
            ->and($user->email)->toBe('existing@example.com');

        // Assert: User has not been created twice
        assertDatabaseCount('users', 1);
    });
});
