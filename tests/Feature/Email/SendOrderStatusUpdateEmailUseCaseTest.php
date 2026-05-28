<?php

namespace Tests\Feature\Email;

use App\Mail\OrderStatusUpdatedMail;
use App\Models\CustomerModel;
use App\Models\UserModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Src\customer\order\application\useCases\SendOrderStatusUpdateEmailUseCase;
use Src\customer\order\domain\valueObjects\OrderStatus;
use Tests\TestCase;

class SendOrderStatusUpdateEmailUseCaseTest extends TestCase
{
    use RefreshDatabase;

    private function makeUserAndCustomer(): array
    {
        $user = UserModel::create([
            'name' => 'Laura Sanz',
            'email' => 'laura@test.com',
            'password' => bcrypt('password'),
        ]);

        $customer = CustomerModel::create([
            'user_id' => $user->id,
            'first_name' => 'Laura',
            'last_name' => 'Sanz',
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

    public function test_sends_status_update_email(): void
    {
        Mail::fake();

        [$user, $customer] = $this->makeUserAndCustomer();

        $useCase = app(SendOrderStatusUpdateEmailUseCase::class);
        $useCase->execute($customer->id, 'ORD-20260528-ABCD', OrderStatus::Processing, 89.95);

        Mail::assertSent(OrderStatusUpdatedMail::class, function (OrderStatusUpdatedMail $mail) use ($user) {
            return $mail->hasTo($user->email)
                && $mail->customerName === 'Laura Sanz'
                && $mail->orderNumber === 'ORD-20260528-ABCD'
                && $mail->newStatus === OrderStatus::Processing
                && $mail->totalAmount === 89.95;
        });
    }

    public function test_sends_email_with_cancelled_status(): void
    {
        Mail::fake();

        [$user, $customer] = $this->makeUserAndCustomer();

        $useCase = app(SendOrderStatusUpdateEmailUseCase::class);
        $useCase->execute($customer->id, 'ORD-20260528-ABCD', OrderStatus::Cancelled, 89.95);

        Mail::assertSent(OrderStatusUpdatedMail::class, function (OrderStatusUpdatedMail $mail) use ($user) {
            return $mail->hasTo($user->email)
                && $mail->newStatus === OrderStatus::Cancelled;
        });
    }

    public function test_does_not_crash_when_customer_not_found(): void
    {
        Mail::fake();

        $useCase = app(SendOrderStatusUpdateEmailUseCase::class);
        $useCase->execute(99999, 'ORD-TEST-001', OrderStatus::Shipped, 50.00);

        Mail::assertNothingSent();
    }
}
