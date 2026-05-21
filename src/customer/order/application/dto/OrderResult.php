<?php

namespace Src\customer\order\application\dto;

use Src\customer\order\domain\entities\Order;

class OrderResult
{
    public function __construct(
        public readonly int $id,
        public readonly string $orderNumber,
        public readonly int $customerId,
        public readonly string $orderDate,
        public readonly float $subtotal,
        public readonly float $shippingCost,
        public readonly float $taxAmount,
        public readonly float $totalAmount,
        public readonly string $status,
        public readonly ?string $trackingNumber,
        public readonly ?string $estimatedDeliveryDate,
        public readonly ?string $customerNotes,
        public readonly array $items,
    ) {}

    public static function fromOrder(Order $order): self
    {
        return new self(
            id: $order->id(),
            orderNumber: $order->orderNumber(),
            customerId: $order->customerId(),
            orderDate: $order->orderDate()->format(\DateTimeInterface::ATOM),
            subtotal: $order->subtotal(),
            shippingCost: $order->shippingCost(),
            taxAmount: $order->taxAmount(),
            totalAmount: $order->totalAmount(),
            status: $order->status()->value(),
            trackingNumber: $order->trackingNumber(),
            estimatedDeliveryDate: $order->estimatedDeliveryDate()?->format('Y-m-d'),
            customerNotes: $order->customerNotes(),
            items: array_map(
                fn ($item) => OrderItemResult::fromOrderItem($item),
                $order->items()
            ),
        );
    }
}