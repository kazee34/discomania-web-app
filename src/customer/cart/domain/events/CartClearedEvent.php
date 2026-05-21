<?php

namespace Src\customer\cart\domain\events;

class CartClearedEvent
{
    public function __construct(
        public readonly ?int $cartId,
    ) {}
}