<?php

namespace Src\customer\payment\application\listeners;

use Src\customer\order\domain\events\OrderCancelledEvent;
use Src\customer\payment\domain\repositories\OrderPaymentRepositoryInterface;

class OrderPaymentRefundOnCancelledOrderListener
{
    public function __construct(
        private OrderPaymentRepositoryInterface $repository
    ) {}

    public function handle(OrderCancelledEvent $event): void
    {
        $orderPayment = $this->repository->findByOrderId($event->orderId);
        if (!$orderPayment) {
            throw new \Exception('Order payment not found for the given order ID.');
        }

        $orderPayment->refund();
        $this->repository->save($orderPayment);
    }
}
