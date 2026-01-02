<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Monitor;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class ShowMonitorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        /** @var ?User $user */
        $user = $this->user();
        /** @var ?Monitor $monitor */
        $monitor = $this->route('monitor');

        return $user
            && $monitor
            && $user->tokenCan('view-monitor:'.$monitor->id);
    }
}
