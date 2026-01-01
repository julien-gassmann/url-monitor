<?php

declare(strict_types=1);

namespace App\Dto;

use App\Enums\InvalidReasonEnum;
use App\Http\Resources\MonitorResource;
use App\Models\Monitor;

final readonly class VerifyAccessTokenResult
{
    public function __construct(
        public bool $isValid,
        public ?InvalidReasonEnum $reason,
        public ?Monitor $monitor,
    ) {}

    /**
     * @return array{
     *     is_valid: bool,
     *     reason: ?InvalidReasonEnum,
     *     monitor: ?MonitorResource
     * }
     */
    public function toArray(): array
    {
        return [
            'is_valid' => $this->isValid,
            'reason' => $this->reason,
            'monitor' => $this->monitor instanceof Monitor
                ? MonitorResource::make($this->monitor)
                : null,
        ];
    }
}
