<?php

declare(strict_types=1);

namespace App\Actions;

use App\Actions\MonitorAccessToken\InvalidateMonitorAccessTokensAction;
use App\Actions\MonitorAccessToken\VerifyMonitorAccessTokenAction;
use App\Actions\User\CreateBearerTokenAction;
use App\Dto\VerifyAccessTokenResult;
use App\Models\MonitorAccessToken;
use Illuminate\Support\Facades\DB;
use Throwable;

final readonly class ProcessAccessTokenVerification
{
    public function __construct(
        private VerifyMonitorAccessTokenAction $verifyAccessToken,
        private InvalidateMonitorAccessTokensAction $invalidatePreviousTokens,
        private CreateBearerTokenAction $createBearerToken,
    ) {}

    /**
     * @throws Throwable
     */
    public function handle(MonitorAccessToken $monitorAccessToken): VerifyAccessTokenResult
    {
        /** @var VerifyAccessTokenResult $result */
        $result = DB::transaction(function () use ($monitorAccessToken): VerifyAccessTokenResult {
            $monitor = $monitorAccessToken->monitor;
            $result = $this->verifyAccessToken->handle($monitorAccessToken);

            if ($result->isValid) {
                $this->invalidatePreviousTokens->onVerify($monitor);
            }

            return $this->createBearerToken->handle($result);
        });

        return $result;
    }
}
