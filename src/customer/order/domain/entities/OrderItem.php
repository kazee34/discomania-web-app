<?php

namespace Src\customer\order\domain\entities;

class OrderItem
{
    public function __construct(
        private readonly ?int $id,
        private readonly ?int $orderId,
        private readonly int $productId,
        private readonly array $productSnapshot,
        private readonly int $quantity,
        private readonly float $pricePerUnit,
    ) {}

    public static function create(
        int $productId,
        array $productSnapshot,
        int $quantity,
        float $pricePerUnit,
    ): self {
        return new self(
            id: null,
            orderId: null,
            productId: $productId,
            productSnapshot: $productSnapshot,
            quantity: $quantity,
            pricePerUnit: $pricePerUnit,
        );
    }

    public static function fromPrimitives(
        int $id,
        int $orderId,
        int $productId,
        array $productSnapshot,
        int $quantity,
        float $pricePerUnit,
    ): self {
        return new self(
            id: $id,
            orderId: $orderId,
            productId: $productId,
            productSnapshot: $productSnapshot,
            quantity: $quantity,
            pricePerUnit: $pricePerUnit,
        );
    }

    public function subtotal(): float
    {
        return round($this->pricePerUnit * $this->quantity, 2);
    }

    public function id(): ?int            { return $this->id; }
    public function orderId(): ?int       { return $this->orderId; }
    public function productId(): int      { return $this->productId; }
    public function productSnapshot(): array { return $this->productSnapshot; }
    public function quantity(): int       { return $this->quantity; }
    public function pricePerUnit(): float { return $this->pricePerUnit; }
}