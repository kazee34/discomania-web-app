<?php

namespace Src\customer\cart\domain\events;

class CartItemQuantityUpdatedEvent
{
    public function __construct(
        public readonly ?int $cartId,
        public readonly int $cartItemId,
        public readonly int $newQuantity,
    ) {}
}
