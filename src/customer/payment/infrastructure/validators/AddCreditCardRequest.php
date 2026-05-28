<?php

namespace Src\customer\payment\infrastructure\validators;

use App\Rules\NoSpecialCharacters;
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
        $currentMonth = (int) date('n');

        return [
            'card_number' => ['required', 'string', 'regex:/^[\d\s]{13,19}$/'],
            'card_brand' => ['required', 'string', 'in:visa,mastercard'],
            'card_holder' => ['required', 'string', 'min:2', 'max:100', new NoSpecialCharacters],
            'expiry_month' => [
                'required', 'integer', 'min:1', 'max:12',
                function ($_attribute, $value, $fail) use ($currentYear, $currentMonth) {
                    $year = (int) $this->input('expiry_year');
                    if ($year === $currentYear && (int) $value < $currentMonth) {
                        $fail('La tarjeta está caducada.');
                    }
                },
            ],
            'expiry_year' => ['required', 'integer', 'min:'.$currentYear, 'max:'.($currentYear + 20)],
            'cvv' => ['required', 'string', 'regex:/^\d{3}$/'],
            'set_as_default' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'card_number.required' => 'El número de tarjeta es obligatorio.',
            'card_number.regex'    => 'El número de tarjeta no es válido.',
            'card_brand.required'  => 'El tipo de tarjeta es obligatorio.',
            'card_brand.in'        => 'Solo se aceptan tarjetas Visa y Mastercard.',
            'card_holder.required' => 'El nombre del titular es obligatorio.',
            'card_holder.min'      => 'El nombre del titular debe tener al menos 2 caracteres.',
            'card_holder.max'      => 'El nombre del titular no puede superar los 100 caracteres.',
            'expiry_month.required' => 'El mes de caducidad es obligatorio.',
            'expiry_month.integer'  => 'El mes de caducidad no es válido.',
            'expiry_month.min'      => 'El mes de caducidad no es válido.',
            'expiry_month.max'      => 'El mes de caducidad no es válido.',
            'expiry_year.required'  => 'El año de caducidad es obligatorio.',
            'expiry_year.integer'   => 'El año de caducidad no es válido.',
            'expiry_year.min'       => 'La tarjeta está caducada.',
            'expiry_year.max'       => 'El año de caducidad no es válido.',
            'cvv.required'          => 'El CVV es obligatorio.',
            'cvv.regex'             => 'El CVV debe tener 3 dígitos.',
            'set_as_default.boolean' => 'El valor de predeterminado no es válido.',
        ];
    }
}
