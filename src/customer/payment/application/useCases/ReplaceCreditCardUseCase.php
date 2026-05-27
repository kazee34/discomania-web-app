<?php

namespace Src\customer\payment\application\useCases;

use Illuminate\Auth\Access\AuthorizationException;
use Src\customer\payment\application\dto\PaymentMethodResult;
use Src\customer\payment\domain\repositories\PaymentMethodRepositoryInterface;

final class ReplaceCreditCardUseCase
{
    public function __construct(
        private PaymentMethodRepositoryInterface $repository,
        private AddCreditCardUseCase $addCreditCard,
        private RemovePaymentMethodUseCase $remove,
    ) {}

    public function execute(
        int $methodId,
        int $customerId,
        string $cardNumber,
        string $cardBrand,
        string $cardHolder,
        int $expiryMonth,
        int $expiryYear,
        bool $setAsDefault,
    ): PaymentMethodResult {
        $method = $this->repository->findById($methodId);

        if ($method->customerId() !== $customerId) {
            throw new AuthorizationException('Este método de pago no pertenece al cliente.');
        }

        $wasDefault = $method->isDefault();

        $this->remove->execute($methodId, $customerId);

        return $this->addCreditCard->execute(
            customerId: $customerId,
            cardNumber: $cardNumber,
            cardBrand: $cardBrand,
            cardHolderName: $cardHolder,
            expiryMonth: $expiryMonth,
            expiryYear: $expiryYear,
            setAsDefault: $setAsDefault || $wasDefault,
        );
    }
}
