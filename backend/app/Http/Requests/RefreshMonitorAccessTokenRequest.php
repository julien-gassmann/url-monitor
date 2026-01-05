<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\MonitorAccessToken;
use Illuminate\Foundation\Http\FormRequest;

class RefreshMonitorAccessTokenRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        /** @var ?MonitorAccessToken $accessToken */
        $accessToken = $this->route('token');

        return $accessToken
            && $accessToken->refreshed_at === null;
    }
}
