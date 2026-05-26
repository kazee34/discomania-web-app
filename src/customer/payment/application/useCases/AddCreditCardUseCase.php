<?php

namespace Src\customer\payment\application\useCases;

use Src\customer\payment\application\dto\PaymentMethodResult;
use Src\customer\payment\domain\entities\CreditCard;
use Src\customer\payment\domain\entities\PaymentMethod;
use Src\customer\payment\domain\repositories\PaymentMethodRepositoryInterface;
use Src\customer\payment\domain\valueObjects\CardBrand;
use Src\customer\payment\domain\valueObjects\CardExpiry;
use Src\customer\payment\domain\valueObjects\CreditCardNumber;

final class AddCreditCardUseCase
{
    public function __construct(
        private PaymentMethodRepositoryInterface $repository,
    ) {}

    public function execute(
        int $customerId,
        string $cardNumber,
        string $cardBrand,
        string $cardHolderName,
        int $expiryMonth,
        int $expiryYear,
        bool $setAsDefault,
    ): PaymentMethodResult {
        $brand = CardBrand::from($cardBrand);
        $number = new CreditCardNumber($cardNumber, $brand);
        new CardExpiry($expiryMonth, $expiryYear);

        if ($setAsDefault) {
            $this->repository->clearDefaultForCustomer($customerId);
        }

        $creditCard = new CreditCard(
            id: null,
            paymentMethodId: null,
            cardHolderName: trim($cardHolderName),
            cardBrand: $brand,
            cardLastFour: $number->lastFour(),
            cardExpiryMonth: $expiryMonth,
            cardExpiryYear: $expiryYear,
        );

        $label = "{$brand->label()} •••• {$number->lastFour()}";

        $method = PaymentMethod::createCreditCard(
            customerId: $customerId,
            label: $label,
            isDefault: $setAsDefault,
            creditCard: $creditCard,
        );

        $id = $this->repository->save($method);
        $method->setId($id);

        return PaymentMethodResult::fromEntity($method);
    }
}
