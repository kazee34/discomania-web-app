<?php

namespace Src\customer\cart\domain\events;

class CartItemRemovedEvent
{
    public function __construct(
        public readonly ?int $cartId,
        public readonly int $cartItemId,
    ) {}
}