<?php

namespace Src\customer\payment\domain\repositories;

use Src\customer\payment\domain\entities\OrderPayment;

interface OrderPaymentRepositoryInterface
{
    public function save(OrderPayment $payment): int;

    public function findByOrderId(int $orderId): ?OrderPayment;
}
