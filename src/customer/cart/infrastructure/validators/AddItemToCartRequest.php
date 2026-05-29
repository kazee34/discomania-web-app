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
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'El producto es obligatorio.',
            'product_id.exists'   => 'El producto seleccionado no existe.',
            'price.required'      => 'El precio es obligatorio.',
            'price.min'           => 'El precio no puede ser negativo.',
            'quantity.required'   => 'La cantidad es obligatoria.',
            'quantity.min'        => 'La cantidad debe ser al menos 1.',
            'quantity.max'        => 'La cantidad no puede superar las 1000 unidades.',
        ];
    }
}
