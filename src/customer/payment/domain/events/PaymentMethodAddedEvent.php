<?php

namespace Src\customer\payment\domain\events;

final class PaymentMethodAddedEvent
{
    public function __construct(
        public readonly int $customerId,
        public readonly int $paymentMethodId,
        public readonly string $type,
    ) {}
}
