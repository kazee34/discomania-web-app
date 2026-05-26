<?php

namespace Src\customer\payment\domain\events;

final class PaymentMethodRemovedEvent
{
    public function __construct(
        public readonly int $customerId,
        public readonly int $paymentMethodId,
    ) {}
}
