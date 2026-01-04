<?php

declare(strict_types=1);

namespace App\Actions\Metadata;

use App\Enums\FrequencyEnum;
use App\Enums\HttpCodeEnum;
use App\Enums\MetadataPageEnum;
use App\Enums\PerPageEnum;

final readonly class GetApiMetadataAction
{
    /**
     * @return array{data: array<string, mixed>}
     */
    public function handle(MetadataPageEnum $page): array
    {
        $data = match ($page) {
            MetadataPageEnum::CREATE_MONITOR => [
                'frequencies' => FrequencyEnum::forSelectDisplay(),
                'http_codes' => HttpCodeEnum::forSelectDisplay(),
            ],
            MetadataPageEnum::PAGINATE_CHECKS => [
                'allowed_per_page' => PerPageEnum::forSelectDisplay(),
            ],
        };

        return ['data' => $data];
    }
}
