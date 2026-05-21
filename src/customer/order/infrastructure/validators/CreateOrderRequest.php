<?php

namespace Src\customer\order\infrastructure\validators;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cart_token'     => 'required|string',
            'customer_id'    => 'required|integer|exists:customers,id',
            'customer_notes' => 'nullable|string|max:1000',
        ];
    }
}