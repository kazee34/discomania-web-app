<?php

namespace Src\customer\payment\domain\events;

final class PaymentProcessedEvent
{
    public function __construct(
        public readonly int $orderId,
        public readonly int $orderPaymentId,
        public readonly float $amount,
        public readonly string $status,
    ) {}
}
