<?php

namespace Src\customer\order\domain\events;

use Src\customer\order\domain\valueObjects\OrderStatus;

class OrderStatusUpdatedEvent
{
    public function __construct(
        public readonly int $customerId,
        public readonly string $orderNumber,
        public readonly OrderStatus $newStatus,
        public readonly float $totalAmount,
    ) {}
}
