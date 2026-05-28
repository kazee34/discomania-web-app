<?php

namespace Tests\Unit\Order;

use PHPUnit\Framework\TestCase;
use Src\customer\order\domain\entities\Order;
use Src\customer\order\domain\entities\OrderItem;

class OrderTest extends TestCase
{
    private function makeItem(float $price, int $quantity): OrderItem
    {
        return OrderItem::create(
            productId: 1,
            productSnapshot: [
                'artist' => 'Test Artist',
                'album_title' => 'Test Album',
                'slug' => 'test-artist-test-album',
                'genre' => 'Rock',
                'cover_image_url' => '',
            ],
            quantity: $quantity,
            pricePerUnit: $price,
        );
    }

    public function test_create_computes_subtotal_and_total(): void
    {
        $order = Order::create(
            customerId: 1,
            items: [
                $this->makeItem(10.00, 2),
                $this->makeItem(5.50, 3),
            ],
        );

        $this->assertEquals(36.50, $order->subtotal());
        $this->assertEquals(36.50, $order->totalAmount());
    }

    public function test_create_includes_shipping_and_tax_in_total(): void
    {
        $order = Order::create(
            customerId: 1,
            items: [$this->makeItem(10.00, 1)],
            shippingCost: 3.00,
            taxAmount: 2.10,
        );

        $this->assertEquals(10.00, $order->subtotal());
        $this->assertEquals(15.10, $order->totalAmount());
    }

    public function test_create_starts_with_pending_status(): void
    {
        $order = Order::create(customerId: 1, items: [$this->makeItem(10.00, 1)]);

        $this->assertTrue($order->status()->isPending());
    }

    public function test_cancel_transitions_to_cancelled(): void
    {
        $order = Order::create(customerId: 1, items: [$this->makeItem(10.00, 1)]);
        $order->cancel();

        $this->assertTrue($order->status()->isCancelled());
        $this->assertFalse($order->status()->isPending());
    }

    public function test_cancel_throws_when_order_is_not_pending(): void
    {
        $order = Order::create(customerId: 1, items: [$this->makeItem(10.00, 1)]);
        $order->cancel();

        $this->expectException(\DomainException::class);
        $order->cancel();
    }

    public function test_cancel_records_cancelled_event(): void
    {
        $order = Order::create(customerId: 1, items: [$this->makeItem(10.00, 1)]);
        $order->releaseEvents(); // flush create event

        $order->cancel();
        $events = $order->releaseEvents();

        $this->assertCount(2, $events);
        $this->assertInstanceOf(
            \Src\customer\order\domain\events\OrderCancelledEvent::class,
            $events[0]
        );
        $this->assertInstanceOf(
            \Src\customer\order\domain\events\OrderStatusUpdatedEvent::class,
            $events[1]
        );
    }
}
