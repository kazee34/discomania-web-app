<?php

namespace Src\customer\cart\infrastructure\validators;

use Illuminate\Foundation\Http\FormRequest;

class AddItemToCartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => 'required|integer|exists:products,id',
            'price'      => 'required|numeric|min:0',
            'quantity'   => 'required|integer|min:1|max:1000',
        ];
    }
}
