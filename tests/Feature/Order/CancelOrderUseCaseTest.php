<?php

namespace Tests\Feature\Order;

use App\Models\CustomerModel;
use App\Models\OrderModel;
use App\Models\OrderPaymentModel;
use App\Models\UserModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Src\customer\order\application\useCases\CancelOrderUseCase;
use Src\customer\order\domain\events\OrderCancelledEvent;
use Src\customer\payment\application\listeners\OrderPaymentRefundOnCancelledOrderListener;
use Tests\TestCase;

class CancelOrderUseCaseTest extends TestCase
{
    use RefreshDatabase;

    private function makeOrderWithPayment(string $orderNumber = 'ORD-TEST-001'): array
    {
        $user = UserModel::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $customer = CustomerModel::create([
            'user_id' => $user->id,
            'first_name' => 'Test',
            'last_name' => 'User',
            'street' => 'Calle Mayor',
            'street_number' => '1',
            'city' => 'Madrid',
            'postal_code' => '28001',
            'country' => 'España',
            'iso_country_code' => 'ES',
        ]);

        $order = OrderModel::create([
            'customer_id' => $customer->id,
            'order_number' => $orderNumber,
            'order_date' => now(),
            'subtotal' => 50.00,
            'shipping_cost' => 5.00,
            'tax_amount' => 10.50,
            'total_amount' => 65.50,
            'order_status' => 'pending',
        ]);

        $payment = OrderPaymentModel::create([
            'order_id' => $order->id,
            'payment_type' => 'credit_card',
            'payment_summary' => 'Visa **** 1234',
            'status' => 'completed',
            'mock_transaction_id' => 'txn_test_'.uniqid(),
            'amount' => 65.50,
            'currency' => 'EUR',
            'processed_at' => now(),
        ]);

        return [$order, $payment];
    }

    public function test_cancel_sets_order_status_to_cancelled(): void
    {
        [$order] = $this->makeOrderWithPayment();

        $useCase = app(CancelOrderUseCase::class);
        $result = $useCase->execute($order->order_number);

        $this->assertEquals('cancelled', $result->status);
        $this->assertDatabaseHas('orders', [
            'order_number' => $order->order_number,
            'order_status' => 'cancelled',
        ]);
    }

    public function test_cancel_dispatches_order_cancelled_event(): void
    {
        [$order] = $this->makeOrderWithPayment();

        Event::fakeFor(function () use ($order) {
            $useCase = app(CancelOrderUseCase::class);
            $useCase->execute($order->order_number);

            Event::assertDispatched(OrderCancelledEvent::class, function (OrderCancelledEvent $event) use ($order) {
                return $event->orderNumber === $order->order_number
                    && $event->orderId === $order->id;
            });
        });
    }

    public function test_cancel_refunds_payment_via_event_listener(): void
    {
        [$order, $payment] = $this->makeOrderWithPayment();

        $event = new OrderCancelledEvent($order->id, $order->customer_id, $order->order_number);
        app(OrderPaymentRefundOnCancelledOrderListener::class)->handle($event);

        $this->assertDatabaseHas('order_payments', [
            'id' => $payment->id,
            'status' => 'refunded',
        ]);
    }
}
