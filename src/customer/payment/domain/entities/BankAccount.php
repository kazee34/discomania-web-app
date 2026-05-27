<?php

namespace Src\customer\payment\domain\entities;

final class BankAccount
{
    public function __construct(
        private ?int $id,
        private ?int $paymentMethodId,
        private string $accountHolderName,
        private string $ibanMasked,
        private string $ibanLastFour,
        private string $ibanFull,
        private ?string $bankName,
    ) {}

    public static function fromPrimitives(
        int $id,
        int $paymentMethodId,
        string $accountHolderName,
        string $ibanMasked,
        string $ibanLastFour,
        string $ibanFull,
        ?string $bankName,
    ): self {
        return new self(
            id: $id,
            paymentMethodId: $paymentMethodId,
            accountHolderName: $accountHolderName,
            ibanMasked: $ibanMasked,
            ibanLastFour: $ibanLastFour,
            ibanFull: $ibanFull,
            bankName: $bankName,
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

    public function accountHolderName(): string
    {
        return $this->accountHolderName;
    }

    public function ibanMasked(): string
    {
        return $this->ibanMasked;
    }

    public function ibanLastFour(): string
    {
        return $this->ibanLastFour;
    }

    public function ibanFull(): string
    {
        return $this->ibanFull;
    }

    public function bankName(): ?string
    {
        return $this->bankName;
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
        $name = $this->bankName ? " ({$this->bankName})" : '';

        return "IBAN •••• {$this->ibanLastFour}{$name}";
    }
}
