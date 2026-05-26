<?php

namespace Src\customer\payment\domain\entities;

use Src\customer\payment\domain\valueObjects\PaymentMethodType;

final class PaymentMethod
{
    public function __construct(
        private ?int $id,
        private int $customerId,
        private PaymentMethodType $type,
        private bool $isDefault,
        private string $label,
        private ?CreditCard $creditCard = null,
        private ?BankAccount $bankAccount = null,
    ) {}

    public static function createCreditCard(
        int $customerId,
        string $label,
        bool $isDefault,
        CreditCard $creditCard,
    ): self {
        return new self(
            id: null,
            customerId: $customerId,
            type: PaymentMethodType::CreditCard,
            isDefault: $isDefault,
            label: $label,
            creditCard: $creditCard,
        );
    }

    public static function createBankAccount(
        int $customerId,
        string $label,
        bool $isDefault,
        BankAccount $bankAccount,
    ): self {
        return new self(
            id: null,
            customerId: $customerId,
            type: PaymentMethodType::SepaDebit,
            isDefault: $isDefault,
            label: $label,
            bankAccount: $bankAccount,
        );
    }

    public static function fromPrimitives(
        int $id,
        int $customerId,
        string $type,
        bool $isDefault,
        string $label,
        ?CreditCard $creditCard = null,
        ?BankAccount $bankAccount = null,
    ): self {
        return new self(
            id: $id,
            customerId: $customerId,
            type: PaymentMethodType::from($type),
            isDefault: $isDefault,
            label: $label,
            creditCard: $creditCard,
            bankAccount: $bankAccount,
        );
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function customerId(): int
    {
        return $this->customerId;
    }

    public function type(): PaymentMethodType
    {
        return $this->type;
    }

    public function isDefault(): bool
    {
        return $this->isDefault;
    }

    public function label(): string
    {
        return $this->label;
    }

    public function creditCard(): ?CreditCard
    {
        return $this->creditCard;
    }

    public function bankAccount(): ?BankAccount
    {
        return $this->bankAccount;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setDefault(bool $value): void
    {
        $this->isDefault = $value;
    }
}
