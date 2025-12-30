<?php

namespace App\Actions;

use App\Enums\FrequencyEnum;
use App\Enums\HttpCodeEnum;

final readonly class GetApiMetadataAction
{
    /**
     * @return array{
     *     frequencies: array<int, array{label: string}>,
     *     http_codes: array<string, array<int, array{code: int, message: string}>>,
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
