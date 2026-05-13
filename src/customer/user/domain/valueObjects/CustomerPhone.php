<?php

namespace Src\customer\user\domain\valueObjects;

use InvalidArgumentException;

class CustomerPhone
{
    public function __construct(private readonly ?string $phone)
    {
        $this->validate();
    }

    private function validate(): void
    {
        if ($this->phone !== null && empty($this->phone)) {
            throw new InvalidArgumentException('Phone cannot be empty string');
        }
        if ($this->phone !== null && strlen($this->phone) > 50) {
            throw new InvalidArgumentException('Phone cannot exceed 50 characters');
        }
    }

    public function value(): ?string
    {
        return $this->phone;
    }

    public function isEmpty(): bool
    {
        return $this->phone === null;
    }
}
