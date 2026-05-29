<?php

namespace Tests\Feature\Email;

use App\Mail\OrderConfirmationMail;
use App\Models\CustomerModel;
use App\Models\OrderItemModel;
use App\Models\OrderModel;
use App\Models\OrderPaymentModel;
use App\Models\ProductModel;
use App\Models\UserModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Src\customer\order\application\useCases\SendOrderConfirmationEmailUseCase;
use Tests\TestCase;

class SendOrderConfirmationEmailUseCaseTest extends TestCase
{
    use RefreshDatabase;

    private function makeUserAndCustomer(): array
    {
        $user = UserModel::create([
            'name' => 'Carlos Ruiz',
            'email' => 'carlos@test.com',
            'password' => bcrypt('password'),
        ]);

        $customer = CustomerModel::create([
            'user_id' => $user->id,
            'first_name' => 'Carlos',
            'last_name' => 'Ruiz',
            'shipping_street' => 'Calle Mayor',
            'shipping_street_number' => '1',
            'shipping_city' => 'Madrid',
            'shipping_postal_code' => '28001',
            'shipping_state_province' => 'Madrid',
            'shipping_country' => 'España',
            'shipping_iso_country_code' => 'ES',
        ]);

        return [$user, $customer];
    }

    private function makeOrder(int $customerId, string $orderNumber): OrderModel
    {
        $product = ProductModel::create([
            'artist' => 'David Bowie',
            'album_title' => 'Ziggy Stardust',
            'slug' => 'ziggy-stardust',
            'price' => 74.35,
            'stock_quantity' => 10,
            'is_active' => true,
        ]);

        $order = OrderModel::create([
            'order_number' => $orderNumber,
            'customer_id' => $customerId,
            'order_date' => now(),
            'subtotal' => 74.35,
            'shipping_cost' => 0.00,
            'tax_amount' => 15.60,
            'discount_amount' => 0.00,
            'total_amount' => 89.95,
            'order_status' => 'pending',
        ]);

        OrderItemModel::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_snapshot' => ['artist' => 'David Bowie', 'album_title' => 'Ziggy Stardust', 'slug' => 'ziggy', 'genre' => 'Rock', 'cover_image_url' => null],
            'quantity' => 1,
            'price_per_unit' => 74.35,
            'subtotal' => 74.35,
        ]);

        OrderPaymentModel::create([
            'order_id' => $order->id,
            'payment_type' => 'credit_card',
            'payment_summary' => 'Visa •••• 1234',
            'status' => 'completed',
            'mock_transaction_id' => 'MOCK-001',
            'amount' => 89.95,
            'currency' => 'EUR',
            'processed_at' => now(),
        ]);

        return $order;
    }

    public function test_sends_order_confirmation_email(): void
    {
        Mail::fake();

        [$user, $customer] = $this->makeUserAndCustomer();
        $this->makeOrder($customer->id, 'ORD-20260528-ABCD');

        $useCase = app(SendOrderConfirmationEmailUseCase::class);
        $useCase->execute($customer->id, 'ORD-20260528-ABCD', 89.95);

        Mail::assertSent(OrderConfirmationMail::class, function (OrderConfirmationMail $mail) use ($user) {
            return $mail->hasTo($user->email)
                && $mail->customerName === 'Carlos Ruiz'
                && $mail->order->orderNumber === 'ORD-20260528-ABCD'
                && $mail->order->totalAmount === 89.95
                && $mail->paymentSummary === 'Visa •••• 1234';
        });
    }

    public function test_does_not_crash_when_customer_not_found(): void
    {
        Mail::fake();

        $useCase = app(SendOrderConfirmationEmailUseCase::class);
        $useCase->execute(99999, 'ORD-TEST-001', 50.00);

        Mail::assertNothingSent();
    }
}
