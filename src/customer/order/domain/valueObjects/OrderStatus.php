<?php

namespace Src\customer\order\domain\valueObjects;

// TODO: move this to enum
class OrderStatus
{
    const PENDING    = 'pending';
    const PROCESSING = 'processing';
    const SHIPPED    = 'shipped';
    const DELIVERED  = 'delivered';
    const CANCELLED  = 'cancelled';

    private static array $valid = [
        self::PENDING,
        self::PROCESSING,
        self::SHIPPED,
        self::DELIVERED,
        self::CANCELLED,
    ];

    private function __construct(
        private readonly string $value,
    ) {}

    public static function pending(): self
    {
        return new self(self::PENDING);
    }

    public static function fromString(string $value): self
    {
        if (! in_array($value, self::$valid, true)) {
            throw new \InvalidArgumentException("Invalid order status: {$value}");
        }

        return new self($value);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function isPending(): bool    { return $this->value === self::PENDING; }
    public function isProcessing(): bool { return $this->value === self::PROCESSING; }
    public function isShipped(): bool    { return $this->value === self::SHIPPED; }
    public function isDelivered(): bool  { return $this->value === self::DELIVERED; }
    public function isCancelled(): bool  { return $this->value === self::CANCELLED; }
}