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
        public BearerToken $bearer = new BearerToken,
    ) {}

    public function withBearerToken(BearerToken $bearer): self
    {
        return new self(
            isValid: $this->isValid,
            reason: $this->reason,
            monitor: $this->monitor,
            bearer: $bearer,
        );
    }

    /**
     * @return array<string, array{
     *     is_valid: bool,
     *     reason: ?string,
     *     monitor_uuid: ?string,
     *     bearer: array{
     *         token: ?string,
     *         expires_at: ?string,
     *     }
     * }>
     */
    public function toArray(): array
    {
        $key = $this->isValid ? 'data' : 'errors';

        return [
            $key => [
                'is_valid' => $this->isValid,
                'reason' => $this->reason?->label(),
                'monitor_uuid' => $this->monitor?->uuid,
                'bearer' => $this->bearer->toArray(),
            ],
        ];
    }

    public function statusCode(): int
    {
        return $this->isValid ? 200 : 401;
    }
}
