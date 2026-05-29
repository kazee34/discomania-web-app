<?php

namespace Src\customer\order\domain\notifications;

use Src\customer\order\application\dto\OrderResult;
use Src\customer\order\domain\valueObjects\OrderStatus;

interface OrderEmailNotifierInterface
{
    public function sendOrderConfirmation(string $email, string $name, OrderResult $order, array $shippingAddress, string $paymentSummary): void;

    public function sendOrderStatusUpdated(string $email, string $name, string $orderNumber, OrderStatus $newStatus, float $totalAmount): void;
}
