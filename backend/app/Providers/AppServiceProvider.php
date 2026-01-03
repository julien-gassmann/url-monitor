<?php

declare(strict_types=1);

namespace App\Providers;

use App\Exceptions\MonitorAccessTokenNotFoundException;
use App\Models\MonitorAccessToken;
use App\Services\AccessTokenVerifier;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Route::bind('token', function (string $token): MonitorAccessToken {
            $hashedToken = AccessTokenVerifier::hash($token);

            return MonitorAccessToken::where('token_hash', $hashedToken)->first()
                ?? throw new MonitorAccessTokenNotFoundException;
        });
    }
}
