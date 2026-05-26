<?php

namespace Src\customer\payment\domain\entities;

use Src\customer\payment\domain\valueObjects\CardBrand;

final class CreditCard
{
    public function __construct(
        private ?int $id,
        private ?int $paymentMethodId,
        private string $cardHolderName,
        private CardBrand $cardBrand,
        private string $cardLastFour,
        private int $cardExpiryMonth,
        private int $cardExpiryYear,
    ) {}

    public static function fromPrimitives(
        int $id,
        int $paymentMethodId,
        string $cardHolderName,
        string $cardBrand,
        string $cardLastFour,
        int $cardExpiryMonth,
        int $cardExpiryYear,
    ): self {
        return new self(
            id: $id,
            paymentMethodId: $paymentMethodId,
            cardHolderName: $cardHolderName,
            cardBrand: CardBrand::from($cardBrand),
            cardLastFour: $cardLastFour,
            cardExpiryMonth: $cardExpiryMonth,
            cardExpiryYear: $cardExpiryYear,
        );
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function paymentMethodId(): ?int
    {
        return $this->paymentMethodId;
    }

    public function cardHolderName(): string
    {
        return $this->cardHolderName;
    }

    public function cardBrand(): CardBrand
    {
        return $this->cardBrand;
    }

    public function cardLastFour(): string
    {
        return $this->cardLastFour;
    }

    public function cardExpiryMonth(): int
    {
        return $this->cardExpiryMonth;
    }

    public function cardExpiryYear(): int
    {
        return $this->cardExpiryYear;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setPaymentMethodId(int $id): void
    {
        $this->paymentMethodId = $id;
    }

    public function summary(): string
    {
        return "{$this->cardBrand->label()} •••• {$this->cardLastFour}";
    }
}
