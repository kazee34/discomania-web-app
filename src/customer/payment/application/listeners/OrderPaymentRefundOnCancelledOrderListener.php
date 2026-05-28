<?php

namespace Src\customer\payment\application\listeners;

use Src\customer\order\domain\events\OrderCancelledEvent;
use Src\customer\payment\application\useCases\RefundOrderPaymentUseCase;

class OrderPaymentRefundOnCancelledOrderListener
{
    public function __construct(
        private RefundOrderPaymentUseCase $refundOrderPaymentUseCase
    ) {}

    public function handle(OrderCancelledEvent $event): void
    {
        $this->refundOrderPaymentUseCase->execute($event->orderId);
    }
}
