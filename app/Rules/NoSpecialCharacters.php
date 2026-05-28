<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NoSpecialCharacters implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value !== trim($value)) {
            $fail('El campo :attribute no puede comenzar ni terminar con espacios en blanco.');
            return;
        }

        if (preg_match('/[*<>{}\[\]\\\\^~|$%`;]/', $value)) {
            $fail('El campo :attribute contiene caracteres no permitidos.');
            return;
        }

        if (preg_match('/--|\/\*|\*\//', $value)) {
            $fail('El campo :attribute contiene secuencias no permitidas.');
        }
    }
}
