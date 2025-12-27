<?php

namespace App\Actions;

use App\Enums\FrequencyEnum;
use App\Enums\HttpCodeEnum;

final readonly class GetApiMetadataAction
{
    /**
     * @return array{
     *     frequencies: array<int, string>,
     *     http_codes: array<string, array<int, string>>,
     * }
     */
    public function handle(): array
    {
        return [
            'frequencies' => FrequencyEnum::forSelectDisplay(),
            'http_codes' => HttpCodeEnum::forSelectDisplay(),
        ];
    }
}
