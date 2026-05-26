<?php

namespace Src\customer\payment\application\dto;

use Src\customer\payment\domain\entities\PaymentMethod;

final class PaymentMethodResult
{
    public function __construct(
        public readonly int $id,
        public readonly string $type,
        public readonly bool $isDefault,
        public readonly string $label,
        public readonly ?array $creditCard,
        public readonly ?array $bankAccount,
    ) {}

    public static function fromEntity(PaymentMethod $method): self
    {
        $creditCard = null;
        $bankAccount = null;

        if ($method->creditCard() !== null) {
            $c = $method->creditCard();
            $creditCard = [
                'cardHolderName' => $c->cardHolderName(),
                'cardBrand' => $c->cardBrand()->value,
                'cardLastFour' => $c->cardLastFour(),
                'cardExpiryMonth' => $c->cardExpiryMonth(),
                'cardExpiryYear' => $c->cardExpiryYear(),
            ];
        }

        if ($method->bankAccount() !== null) {
            $b = $method->bankAccount();
            $bankAccount = [
                'accountHolderName' => $b->accountHolderName(),
                'ibanMasked' => $b->ibanMasked(),
                'ibanLastFour' => $b->ibanLastFour(),
                'bic' => $b->bic(),
                'bankName' => $b->bankName(),
            ];
        }

        return new self(
            id: $method->id(),
            type: $method->type()->value,
            isDefault: $method->isDefault(),
            label: $method->label(),
            creditCard: $creditCard,
            bankAccount: $bankAccount,
        );
    }
}
