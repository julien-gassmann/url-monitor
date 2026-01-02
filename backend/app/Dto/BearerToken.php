<?php

declare(strict_types=1);

namespace App\Dto;

use Carbon\Carbon;

final readonly class BearerToken
{
    public function __construct(
        public ?string $accessToken = null,
        public ?Carbon $expiresAt = null,
        public ?string $tokenType = null,
    ) {}

    /**
     * @return array{
     *     access_token: ?string,
     *     token_type: ?string,
     *     expires_at: ?string
     * }
     */
    public function toArray(): array
    {
        return [
            'access_token' => $this->accessToken,
            'token_type' => $this->tokenType,
            'expires_at' => $this->expiresAt?->toISOString(),
        ];
    }
}
