<?php

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
        return [
            'url' => ['required', 'url:http,https', 'between:10,255'],
            'expected_http_code' => ['required', Rule::enum(HttpCodeEnum::class)],
            'frequency' => ['required', Rule::enum(FrequencyEnum::class)],
            'email' => ['required', 'email', 'between:5,100'],
        ];
    }
}
