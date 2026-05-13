<?php

namespace Src\customer\user\domain\valueObjects;

use InvalidArgumentException;

class TaxInformation
{
    public function __construct(
        private readonly ?string $name,
        private readonly ?string $vatNumber,
    ) {
        $this->validate();
    }

    private function validate(): void
    {
        if ($this->name !== null && empty($this->name)) {
            throw new InvalidArgumentException('Tax name cannot be empty string');
        }
        if ($this->vatNumber !== null && empty($this->vatNumber)) {
            throw new InvalidArgumentException('VAT number cannot be empty string');
        }
    }

    public function name(): ?string
    {
        return $this->name;
    }

    public function vatNumber(): ?string
    {
        return $this->vatNumber;
    }

    public function isEmpty(): bool
    {
        return $this->name === null && $this->vatNumber === null;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'vat_number' => $this->vatNumber,
        ];
    }
}
