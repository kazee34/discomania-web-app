<?php

namespace Src\customer\payment\application\dto;

use Src\customer\payment\domain\entities\OrderPayment;

final class OrderPaymentResult
{
    public function __construct(
        public readonly int $id,
        public readonly string $paymentType,
        public readonly string $paymentSummary,
        public readonly string $status,
        public readonly string $mockTransactionId,
        public readonly float $amount,
        public readonly string $currency,
        public readonly string $processedAt,
    ) {}

    public static function fromEntity(OrderPayment $payment): self
    {
        return new self(
            id: $payment->id(),
            paymentType: $payment->paymentType()->value,
            paymentSummary: $payment->paymentSummary(),
            status: $payment->status()->value,
            mockTransactionId: $payment->mockTransactionId(),
            amount: $payment->amount(),
            currency: $payment->currency(),
            processedAt: $payment->processedAt()->format('Y-m-d\TH:i:sP'),
        );
    }
}
