<?php

namespace Src\customer\payment\application\useCases;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Str;
use Src\customer\payment\application\dto\OrderPaymentResult;
use Src\customer\payment\domain\entities\OrderPayment;
use Src\customer\payment\domain\repositories\OrderPaymentRepositoryInterface;
use Src\customer\payment\domain\repositories\PaymentMethodRepositoryInterface;
use Src\customer\payment\domain\valueObjects\CardBrand;
use Src\customer\payment\domain\valueObjects\CardExpiry;
use Src\customer\payment\domain\valueObjects\CreditCardNumber;
use Src\customer\payment\domain\valueObjects\IbanNumber;
use Src\customer\payment\domain\valueObjects\PaymentMethodType;

final class ProcessPaymentUseCase
{
    public function __construct(
        private PaymentMethodRepositoryInterface $paymentMethodRepo,
        private OrderPaymentRepositoryInterface $orderPaymentRepo,
    ) {}

    public function execute(
        int $orderId,
        int $customerId,
        float $amount,
        ?int $paymentMethodId,
        ?array $inlineData,
    ): OrderPaymentResult {
        if ($paymentMethodId !== null) {
            return $this->processWithSavedMethod($orderId, $customerId, $amount, $paymentMethodId);
        }

        return $this->processWithInlineData($orderId, $amount, $inlineData);
    }

    private function processWithSavedMethod(
        int $orderId,
        int $customerId,
        float $amount,
        int $paymentMethodId,
    ): OrderPaymentResult {
        $method = $this->paymentMethodRepo->findById($paymentMethodId);

        if ($method->customerId() !== $customerId) {
            throw new AuthorizationException('El método de pago no pertenece a este cliente.');
        }

        $summary = $method->creditCard()?->summary()
            ?? $method->bankAccount()?->summary()
            ?? $method->label();

        $payment = OrderPayment::create(
            orderId: $orderId,
            paymentMethodId: $paymentMethodId,
            paymentType: $method->type(),
            paymentSummary: $summary,
            amount: $amount,
            mockTransactionId: (string) Str::uuid(),
        );

        $this->orderPaymentRepo->save($payment);

        return OrderPaymentResult::fromEntity($payment);
    }

    private function processWithInlineData(
        int $orderId,
        float $amount,
        array $data,
    ): OrderPaymentResult {
        $type = PaymentMethodType::from($data['type']);

        if ($type === PaymentMethodType::CreditCard) {
            $brand = CardBrand::from($data['card_brand']);
            $number = new CreditCardNumber($data['card_number'], $brand);
            new CardExpiry((int) $data['expiry_month'], (int) $data['expiry_year']);
            $summary = "{$brand->label()} •••• {$number->lastFour()}";
        } else {
            $iban = new IbanNumber($data['iban']);
            $summary = "IBAN •••• {$iban->lastFour()}";
        }

        $payment = OrderPayment::create(
            orderId: $orderId,
            paymentMethodId: null,
            paymentType: $type,
            paymentSummary: $summary,
            amount: $amount,
            mockTransactionId: (string) Str::uuid(),
        );

        $this->orderPaymentRepo->save($payment);

        return OrderPaymentResult::fromEntity($payment);
    }
}
