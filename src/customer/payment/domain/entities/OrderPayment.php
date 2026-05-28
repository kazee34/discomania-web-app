<?php

namespace Src\customer\payment\domain\entities;

use DateTimeImmutable;
use Src\customer\payment\domain\valueObjects\PaymentMethodType;
use Src\customer\payment\domain\valueObjects\PaymentStatus;

final class OrderPayment
{
    public function __construct(
        private ?int $id,
        private int $orderId,
        private ?int $paymentMethodId,
        private PaymentMethodType $paymentType,
        private string $paymentSummary,
        private PaymentStatus $status,
        private string $mockTransactionId,
        private float $amount,
        private string $currency,
        private DateTimeImmutable $processedAt,
    ) {}

    public function id(): ?int
    {
        return $this->id;
    }

    public function orderId(): int
    {
        return $this->orderId;
    }

    public function paymentMethodId(): ?int
    {
        return $this->paymentMethodId;
    }

    public function paymentType(): PaymentMethodType
    {
        return $this->paymentType;
    }

    public function paymentSummary(): string
    {
        return $this->paymentSummary;
    }

    public function status(): PaymentStatus
    {
        return $this->status;
    }

    public function mockTransactionId(): string
    {
        return $this->mockTransactionId;
    }

    public function amount(): float
    {
        return $this->amount;
    }

    public function currency(): string
    {
        return $this->currency;
    }

    public function processedAt(): DateTimeImmutable
    {
        return $this->processedAt;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public static function create(
        int $orderId,
        ?int $paymentMethodId,
        PaymentMethodType $paymentType,
        string $paymentSummary,
        float $amount,
        string $mockTransactionId,
    ): self {
        return new self(
            id: null,
            orderId: $orderId,
            paymentMethodId: $paymentMethodId,
            paymentType: $paymentType,
            paymentSummary: $paymentSummary,
            status: PaymentStatus::Completed,
            mockTransactionId: $mockTransactionId,
            amount: $amount,
            currency: 'EUR',
            processedAt: new DateTimeImmutable,
        );
    }

    public static function fromPrimitives(
        int $id,
        int $orderId,
        ?int $paymentMethodId,
        string $paymentType,
        string $paymentSummary,
        string $status,
        string $mockTransactionId,
        float $amount,
        string $currency,
        string $processedAt,
    ): self {
        return new self(
            id: $id,
            orderId: $orderId,
            paymentMethodId: $paymentMethodId,
            paymentType: PaymentMethodType::from($paymentType),
            paymentSummary: $paymentSummary,
            status: PaymentStatus::from($status),
            mockTransactionId: $mockTransactionId,
            amount: $amount,
            currency: $currency,
            processedAt: new DateTimeImmutable($processedAt),
        );
    }

    public function refund(): void
    {
        $this->status = PaymentStatus::Refunded;
    }

}
