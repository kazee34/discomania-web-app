<?php

namespace Src\customer\cart\infrastructure\validators;

use Illuminate\Foundation\Http\FormRequest;

class CreateCartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => 'nullable|integer|exists:customers,user_id',
            'session_id' => 'nullable|string|max:100',
            'expires_at' => 'nullable|date',
        ];
    }
}
