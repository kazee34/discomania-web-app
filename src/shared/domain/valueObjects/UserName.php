<?php

namespace Src\shared\domain\valueObjects;

use InvalidArgumentException;

class UserName
{
    public function __construct(
        private string $name
    ) {
        $this->validate($name);
        $this->name = $name;
    }

    private function validate(string $name): void
    {
        if (strlen(trim($name)) < 1) {
            throw new InvalidArgumentException('Name cannot be empty.');
        }

        // Aquí podrías añadir más validaciones
    }

    public function value(): string
    {
        return $this->name;
    }
}
