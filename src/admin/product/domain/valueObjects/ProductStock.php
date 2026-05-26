<?php

namespace Src\admin\product\domain\valueObjects;

class ProductStock
{
    private const MIN_STOCK = 0;

    private const MAX_STOCK = 1000;

    public function __construct(private readonly int $value)
    {
        $this->validate($value);
    }

    private function validate(int $value): void
    {
        if ($value < self::MIN_STOCK) {
            throw new \InvalidArgumentException(
                sprintf('Stock cannot be negative. Given: %d', $value)
            );
        }

        if ($value > self::MAX_STOCK) {
            throw new \InvalidArgumentException(
                sprintf('Stock exceeds maximum allowed (%d). Given: %d',
                    self::MAX_STOCK,
                    $value
                )
            );
        }
    }

    public function value(): int
    {
        return $this->value;
    }

    public function isLow(int $threshold = 5): bool
    {
        return $this->value <= $threshold;
    }

    public function isAvailable(): bool
    {
        return $this->value > 0;
    }

    public function decrease(int $quantity): self
    {
        return new self($this->value - $quantity);
    }

    public function increase(int $quantity): self
    {
        return new self($this->value + $quantity);
    }
}
