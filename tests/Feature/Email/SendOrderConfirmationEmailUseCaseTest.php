<?php

namespace Tests\Feature\Email;

use App\Mail\OrderConfirmationMail;
use App\Models\CustomerModel;
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
            'shipping_state_province' => '',
            'shipping_country' => 'España',
            'shipping_iso_country_code' => 'ES',
        ]);

        return [$user, $customer];
    }

    public function test_sends_order_confirmation_email(): void
    {
        Mail::fake();

        [$user, $customer] = $this->makeUserAndCustomer();

        $useCase = app(SendOrderConfirmationEmailUseCase::class);
        $useCase->execute($customer->id, 'ORD-20260528-ABCD', 89.95);

        Mail::assertSent(OrderConfirmationMail::class, function (OrderConfirmationMail $mail) use ($user) {
            return $mail->hasTo($user->email)
                && $mail->customerName === 'Carlos Ruiz'
                && $mail->orderNumber === 'ORD-20260528-ABCD'
                && $mail->totalAmount === 89.95;
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
