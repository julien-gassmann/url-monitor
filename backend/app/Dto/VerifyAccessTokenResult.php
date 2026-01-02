<?php

declare(strict_types=1);

namespace App\Dto;

use App\Enums\InvalidReasonEnum;
use App\Models\Monitor;

final readonly class VerifyAccessTokenResult
{
    public function __construct(
        public bool $isValid,
        public ?InvalidReasonEnum $reason,
        public ?Monitor $monitor,
    ) {}

    public function statusCode(): int
    {
        return $this->isValid ? 200 : 422;
    }

    /**
     * @return array{
     *     is_valid: bool,
     *     reason: ?InvalidReasonEnum,
     *     monitor_uuid: ?string
     * }
     */
    public function toArray(): array
    {
        return [
            'is_valid' => $this->isValid,
            'reason' => $this->reason?->label(),
            'monitor_uuid' => $this->monitor?->uuid,
        ];
    }
}
