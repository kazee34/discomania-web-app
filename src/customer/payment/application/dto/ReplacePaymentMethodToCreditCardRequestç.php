<?php

namespace Src\customer\payment\application\dto;

class ReplacePaymentMethodToCreditCardRequest
{
    public function __construct(
        public readonly ?int $methodId,
        public readonly int $customerId,
        public readonly string $type,
        public readonly bool $isDefault,
        public readonly string $label,
        public readonly string $cardHolderName,
        public readonly string $cardBrand,
        public readonly string $cardLastFour,
        public readonly int $cardExpiryMonth,
        public readonly int $cardExpiryYear
    ) {}
}
