<?php

namespace Src\customer\order\application\dto;

use Src\customer\order\domain\entities\OrderItem;

class OrderItemResult
{
    public function __construct(
        public readonly int $id,
        public readonly int $productId,
        public readonly array $productSnapshot,
        public readonly int $quantity,
        public readonly float $pricePerUnit,
        public readonly float $subtotal,
    ) {}

    public static function fromOrderItem(OrderItem $item): self
    {
        return new self(
            id: $item->id(),
            productId: $item->productId(),
            productSnapshot: $item->productSnapshot(),
            quantity: $item->quantity(),
            pricePerUnit: $item->pricePerUnit(),
            subtotal: $item->subtotal(),
        );
    }
}