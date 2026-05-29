<?php

namespace Src\customer\order\infrastructure\notifications;

use App\Mail\OrderConfirmationMail;
use App\Mail\OrderStatusUpdatedMail;
use Illuminate\Support\Facades\Mail;
use Src\customer\order\application\dto\OrderResult;
use Src\customer\order\domain\notifications\OrderEmailNotifierInterface;
use Src\customer\order\domain\valueObjects\OrderStatus;

class LaravelOrderEmailNotifier implements OrderEmailNotifierInterface
{
    public function sendOrderConfirmation(string $email, string $name, OrderResult $order, array $shippingAddress, string $paymentSummary): void
    {
        Mail::to($email)->send(new OrderConfirmationMail($name, $order, $shippingAddress, $paymentSummary));
    }

    public function sendOrderStatusUpdated(string $email, string $name, string $orderNumber, OrderStatus $newStatus, float $totalAmount): void
    {
        Mail::to($email)->send(new OrderStatusUpdatedMail($name, $orderNumber, $newStatus, $totalAmount));
    }
}
