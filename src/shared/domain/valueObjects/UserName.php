<?php

namespace src\shared\domain\valueObjects;

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
        if (strlen($name) < 3) {
            throw new InvalidArgumentException('Username must be at least 3 characters');
        }

        // Aquí podrías añadir más validaciones
    }

    public function value(): string
    {
        return $this->name;
    }
}
