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
            'iban.required' => 'El IBAN es obligatorio.',
            'iban.regex' => 'El formato del IBAN no es válido.',
            'account_holder.required' => 'El nombre del titular es obligatorio.',
        ];
    }
}
