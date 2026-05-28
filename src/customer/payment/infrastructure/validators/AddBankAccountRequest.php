<?php

namespace Src\customer\payment\infrastructure\validators;

use Illuminate\Foundation\Http\FormRequest;

class AddBankAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'iban' => ['required', 'string', 'regex:/^ES\d{22}$/i'],
            'account_holder' => ['required', 'string', 'min:2', 'max:100'],
            'bank_name' => ['nullable', 'string', 'max:100'],
            'set_as_default' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'iban.required'              => 'El IBAN es obligatorio.',
            'iban.regex'                 => 'El formato del IBAN no es válido. Debe ser un IBAN español (ES + 22 dígitos).',
            'account_holder.required'    => 'El nombre del titular es obligatorio.',
            'account_holder.min'         => 'El nombre del titular debe tener al menos 2 caracteres.',
            'account_holder.max'         => 'El nombre del titular no puede superar los 100 caracteres.',
            'bank_name.max'              => 'El nombre del banco no puede superar los 100 caracteres.',
            'set_as_default.boolean'     => 'El valor de predeterminado no es válido.',
        ];
    }
}
