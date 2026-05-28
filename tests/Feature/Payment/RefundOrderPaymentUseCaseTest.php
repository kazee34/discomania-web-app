<?php

namespace Tests\Feature\Payment;

use App\Models\CustomerModel;
use App\Models\OrderModel;
use App\Models\OrderPaymentModel;
use App\Models\UserModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Src\customer\payment\application\useCases\RefundOrderPaymentUseCase;
use Src\customer\payment\domain\exceptions\PaymentMethodNotFoundException;
use Tests\TestCase;

class RefundOrderPaymentUseCaseTest extends TestCase
{
    use RefreshDatabase;

    private function makeOrderWithPayment(): array
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
            'order_number' => 'ORD-TEST-001',
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

    public function test_refunds_payment_by_order_id(): void
    {
        [$order, $payment] = $this->makeOrderWithPayment();

        /** @var RefundOrderPaymentUseCase $useCase */
        $useCase = app(RefundOrderPaymentUseCase::class);
        $useCase->execute($order->id);

        $this->assertDatabaseHas('order_payments', [
            'id' => $payment->id,
            'status' => 'refunded',
        ]);
    }

    public function test_throws_when_no_payment_exists_for_order(): void
    {
        $this->expectException(PaymentMethodNotFoundException::class);

        $useCase = app(RefundOrderPaymentUseCase::class);
        $useCase->execute(99999);
    }
}
