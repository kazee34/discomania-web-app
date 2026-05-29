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
        if ($this->phone === null) {
            return;
        }
        if (empty($this->phone)) {
            throw new InvalidArgumentException('Phone cannot be empty string');
        }
        if (!preg_match('/^\+?[\d\s\-]{6,20}$/', $this->phone)) {
            throw new InvalidArgumentException('Phone must contain only digits, spaces, hyphens or a leading +');
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
