<?php

namespace Src\customer\payment\application\useCases;

use Src\customer\payment\domain\repositories\OrderPaymentRepositoryInterface;
use Src\customer\payment\domain\exceptions\PaymentMethodNotFoundException;

class RefundOrderPaymentUseCase
{

    public function __construct(
        private OrderPaymentRepositoryInterface $repository
    ) {}

    public function execute(int $orderPaymentId): void
    {
        $paymentMethod = $this->repository->findByOrderId($orderPaymentId);
        if (!$paymentMethod) {
            throw new PaymentMethodNotFoundException($orderPaymentId);
        }

        $paymentMethod->refund();
        $this->repository->save($paymentMethod);
    }

}