<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Enums\HttpCodeEnum;
use App\Enums\StatusEnum;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property ?HttpCodeEnum $http_code
 * @property StatusEnum $status
 * @property Carbon $checked_at
 */
class MonitorCheckResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'http_code' => $this->http_code?->code(),
            'status' => $this->status->label(),
            'checked_at' => $this->checked_at->toDateTimeString(),
        ];
    }
}
