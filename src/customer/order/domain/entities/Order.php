<?php

namespace Src\customer\order\domain\entities;

use Src\customer\order\domain\events\OrderCancelledEvent;
use Src\customer\order\domain\events\OrderCreatedEvent;
use Src\customer\order\domain\valueObjects\OrderStatus;

class Order
{
    private array $domainEvents = [];

    private array $items;

    public function __construct(
        private readonly ?int $id,
        private readonly string $orderNumber,
        private readonly int $customerId,
        private readonly \DateTimeImmutable $orderDate,
        private readonly float $subtotal,
        private readonly float $shippingCost,
        private readonly float $taxAmount,
        private readonly float $totalAmount,
        private OrderStatus $status,
        private readonly ?string $trackingNumber,
        private readonly ?\DateTimeImmutable $estimatedDeliveryDate,
        private readonly ?string $customerNotes,
        private readonly ?string $adminNotes,
        array $items = [],
    ) {
        $this->items = $items;
    }

    public static function create(
        int $customerId,
        array $items,
        float $shippingCost = 0.0,
        float $taxAmount = 0.0,
        ?string $customerNotes = null,
    ): self {
        $subtotal = round(array_sum(array_map(fn (OrderItem $i) => $i->subtotal(), $items)), 2);
        $totalAmount = round($subtotal + $shippingCost + $taxAmount, 2);

        $order = new self(
            id: null,
            orderNumber: self::generateOrderNumber(),
            customerId: $customerId,
            orderDate: new \DateTimeImmutable,
            subtotal: $subtotal,
            shippingCost: $shippingCost,
            taxAmount: $taxAmount,
            totalAmount: $totalAmount,
            status: OrderStatus::Pending,
            trackingNumber: null,
            estimatedDeliveryDate: null,
            customerNotes: $customerNotes,
            adminNotes: null,
            items: $items,
        );

        $order->recordEvent(new OrderCreatedEvent($customerId, $order->orderNumber, $totalAmount));

        return $order;
    }

    public static function fromPrimitives(
        int $id,
        string $orderNumber,
        int $customerId,
        \DateTimeImmutable $orderDate,
        float $subtotal,
        float $shippingCost,
        float $taxAmount,
        float $totalAmount,
        string $status,
        ?string $trackingNumber,
        ?\DateTimeImmutable $estimatedDeliveryDate,
        ?string $customerNotes,
        ?string $adminNotes,
        array $items = [],
    ): self {
        return new self(
            id: $id,
            orderNumber: $orderNumber,
            customerId: $customerId,
            orderDate: $orderDate,
            subtotal: $subtotal,
            shippingCost: $shippingCost,
            taxAmount: $taxAmount,
            totalAmount: $totalAmount,
            status: OrderStatus::from($status),
            trackingNumber: $trackingNumber,
            estimatedDeliveryDate: $estimatedDeliveryDate,
            customerNotes: $customerNotes,
            adminNotes: $adminNotes,
            items: $items,
        );
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function orderNumber(): string
    {
        return $this->orderNumber;
    }

    public function customerId(): int
    {
        return $this->customerId;
    }

    public function orderDate(): \DateTimeImmutable
    {
        return $this->orderDate;
    }

    public function subtotal(): float
    {
        return $this->subtotal;
    }

    public function shippingCost(): float
    {
        return $this->shippingCost;
    }

    public function taxAmount(): float
    {
        return $this->taxAmount;
    }

    public function totalAmount(): float
    {
        return $this->totalAmount;
    }

    public function status(): OrderStatus
    {
        return $this->status;
    }

    public function trackingNumber(): ?string
    {
        return $this->trackingNumber;
    }

    public function estimatedDeliveryDate(): ?\DateTimeImmutable
    {
        return $this->estimatedDeliveryDate;
    }

    public function customerNotes(): ?string
    {
        return $this->customerNotes;
    }

    public function adminNotes(): ?string
    {
        return $this->adminNotes;
    }

    public function items(): array
    {
        return $this->items;
    }

    public function cancel(): void
    {
        if (! $this->status->isPending()) {
            throw new \DomainException("Only pending orders can be cancelled. Current status: {$this->status->value}.");
        }

        $this->status = OrderStatus::Cancelled;
        $this->recordEvent(new OrderCancelledEvent($this->customerId, $this->orderNumber));
    }

    public function recordEvent(object $event): void
    {
        $this->domainEvents[] = $event;
    }

    public function releaseEvents(): array
    {
        $events = $this->domainEvents;
        $this->domainEvents = [];

        return $events;
    }

    private static function generateOrderNumber(): string
    {
        return 'ORD-'.date('Ymd').'-'.strtoupper(bin2hex(random_bytes(4)));
    }
}
