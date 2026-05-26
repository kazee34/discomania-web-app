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
            'iban' => ['required', 'string', 'min:15', 'max:34', 'regex:/^[A-Za-z]{2}[0-9]{2}[A-Za-z0-9\s]{4,30}$/'],
            'account_holder' => ['required', 'string', 'min:2', 'max:100'],
            'bic' => ['nullable', 'string', 'regex:/^[A-Z]{6}[A-Z0-9]{2}([A-Z0-9]{3})?$/i'],
            'bank_name' => ['nullable', 'string', 'max:100'],
            'set_as_default' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'iban.required' => 'El IBAN es obligatorio.',
            'iban.regex' => 'El formato del IBAN no es válido.',
            'account_holder.required' => 'El nombre del titular es obligatorio.',
            'bic.regex' => 'El formato del BIC/SWIFT no es válido.',
        ];
    }
}
