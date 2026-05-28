<?php

namespace Src\customer\order\domain\events;

class OrderCancelledEvent
{
    public function __construct(
        public readonly int $orderId,
        public readonly int $customerId,
        public readonly string $orderNumber,
    ) {}
}
