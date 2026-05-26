<?php

namespace Src\customer\payment\infrastructure\validators;

use Illuminate\Foundation\Http\FormRequest;

class AddCreditCardRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $currentYear = (int) date('Y');

        return [
            'card_number' => ['required', 'string', 'regex:/^[\d\s]{13,19}$/'],
            'card_brand' => ['required', 'string', 'in:visa,mastercard'],
            'card_holder' => ['required', 'string', 'min:2', 'max:100', 'regex:/^[\p{L}\s\-\.\']+$/u'],
            'expiry_month' => ['required', 'integer', 'min:1', 'max:12'],
            'expiry_year' => ['required', 'integer', 'min:'.$currentYear, 'max:'.($currentYear + 20)],
            'cvv' => ['required', 'string', 'regex:/^\d{3}$/'],
            'set_as_default' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'card_number.required' => 'El número de tarjeta es obligatorio.',
            'card_brand.in' => 'Solo se aceptan tarjetas Visa y Mastercard.',
            'card_holder.required' => 'El nombre del titular es obligatorio.',
            'expiry_month.min' => 'El mes de caducidad no es válido.',
            'expiry_year.min' => 'La tarjeta está caducada.',
            'cvv.regex' => 'El CVV debe tener 3 dígitos.',
        ];
    }
}
