<?php

declare(strict_types=1);

namespace App\Dto;

use Carbon\Carbon;

final readonly class BearerToken
{
    public function __construct(
        public ?string $token = null,
        public ?Carbon $expiresAt = null,
    ) {}

    /**
     * @return array{
     *     token: ?string,
     *     expires_at: ?string
     * }
     */
    public function toArray(): array
    {
        return [
            'token' => $this->token,
            'expires_at' => $this->expiresAt?->toISOString(),
        ];
    }
}
