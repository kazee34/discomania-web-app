<?php

namespace App\Http\Requests\Settings;

use App\Concerns\ProfileValidationRules;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
{
    use ProfileValidationRules;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return $this->profileRules($this->user()->id);
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'name.max'      => 'El nombre no puede superar los 255 caracteres.',
            'email.required'=> 'El correo electrónico es obligatorio.',
            'email.email'   => 'El formato del correo electrónico no es válido.',
            'email.max'     => 'El correo electrónico no puede superar los 255 caracteres.',
            'email.unique'  => 'Este correo electrónico ya está en uso.',
        ];
    }
}
