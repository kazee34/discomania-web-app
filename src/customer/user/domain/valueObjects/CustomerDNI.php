<?php

namespace Src\customer\user\domain\valueObjects;

use InvalidArgumentException;

class CustomerDNI
{
    public function __construct(private readonly ?string $dniNif)
    {
        $this->validate();
    }

    private function validate(): void
    {
        if ($this->dniNif !== null && empty($this->dniNif)) {
            throw new InvalidArgumentException('DNI/NIF cannot be empty string');
        }
        if ($this->dniNif !== null && strlen($this->dniNif) > 20) {
            throw new InvalidArgumentException('DNI/NIF cannot exceed 20 characters');
        }
    }

    public function value(): ?string
    {
        return $this->dniNif;
    }

    public function isEmpty(): bool
    {
        return $this->dniNif === null;
    }
}
