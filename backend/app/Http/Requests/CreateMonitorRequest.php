<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\FrequencyEnum;
use App\Enums\HttpCodeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class CreateMonitorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, (Enum|string)[]>
     */
    public function rules(): array
    {
        $isRequired = $this->routeIs('monitors.create')
            ? 'required'
            : 'sometimes';

        return [
            'url' => [$isRequired, 'string', 'url:http,https', 'min:11', 'max:255'],
            'expected_http_code' => [$isRequired, Rule::enum(HttpCodeEnum::class)],
            'frequency' => [$isRequired, Rule::enum(FrequencyEnum::class)],
            'user_email' => [$isRequired, 'string', 'email', 'min:6', 'max:100'],
        ];
    }
}
