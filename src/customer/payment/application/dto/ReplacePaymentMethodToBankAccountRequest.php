<?php

namespace Src\customer\payment\application\dto;

class ReplacePaymentMethodToBankAccountRequest
{
    public function __construct(
        public readonly int $methodId,
        public readonly int $customerId,
        public readonly string $iban,
        public readonly string $accountHolder,
        public readonly ?string $bankName,
        public readonly bool $setAsDefault
    ) {}
}
