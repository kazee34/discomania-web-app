<?php

namespace Src\customer\cart\domain\events;

class CartItemAddedEvent
{
    public function __construct(
        public readonly ?int $cartId,
        public readonly int $productId,
        public readonly float $price,
        public readonly int $quantity,
    ) {}
}