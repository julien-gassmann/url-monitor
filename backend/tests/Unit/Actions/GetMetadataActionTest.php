<?php

use App\Actions\GetMetadataAction;
use App\Enums\FrequencyEnum;
use App\Enums\HttpCodeEnum;
use App\Enums\MetadataPageEnum;
use App\Enums\PerPageEnum;

/**
 * ───────────────────────────────────────
 * Valid scenarios for Action GetMetadataAction
 * ───────────────────────────────────────
 */
describe('Actions - GetMetadataAction : success', function (): void {
    it('returns data for page create-monitor', function (): void {
        // Act: Call action's handle method
        /** @noinspection PhpUnhandledExceptionInspection */
        $data = app(GetMetadataAction::class)->handle(MetadataPageEnum::CREATE_MONITOR);

        // Assert: Datas are expected ones
        expect($data)->toHaveKey('data', [
            'frequencies' => FrequencyEnum::forSelectDisplay(),
            'http_codes' => HttpCodeEnum::forSelectDisplay(),
        ]);
    });

    it('returns data for page paginate-checks', function (): void {
        // Act: Call action's handle method
        /** @noinspection PhpUnhandledExceptionInspection */
        $data = app(GetMetadataAction::class)->handle(MetadataPageEnum::PAGINATE_CHECKS);

        // Assert: Datas are expected ones
        expect($data)->toHaveKey('data', [
            'allowed_per_page' => PerPageEnum::forSelectDisplay(),
        ]);
    });
});
