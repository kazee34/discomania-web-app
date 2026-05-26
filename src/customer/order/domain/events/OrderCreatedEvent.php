<?php

namespace Src\customer\order\domain\events;

class OrderCreatedEvent
{
    public function __construct(
        public readonly int $customerId,
        public readonly string $orderNumber,
        public readonly float $totalAmount,
    ) {}
}
