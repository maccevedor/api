<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'monthly_price' => ['required', 'numeric', 'min:0'],
            'user_limit' => ['required', 'integer', 'min:1'],
            'features' => ['required', 'array', 'min:1'],
            'features.*.name' => ['required', 'string', 'max:255'],
            'features.*.description' => ['required', 'string']
        ];
    }
}
