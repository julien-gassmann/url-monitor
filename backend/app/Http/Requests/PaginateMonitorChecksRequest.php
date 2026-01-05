<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\PerPageEnum;
use App\Models\Monitor;
use App\Models\MonitorCheck;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rules\In;

class PaginateMonitorChecksRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, (string|Enum|In)[]>
     */
    public function rules(): array
    {
        return [
            'page' => ['required', 'integer', 'gte:1'],
            'per_page' => ['required', 'integer', Rule::enum(PerPageEnum::class)],
            'sort' => ['nullable', 'string', Rule::in(MonitorCheck::getAllowedAttributesForSort())],
        ];
    }
}
