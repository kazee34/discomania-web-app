<?php

namespace Tests\Feature\Email;

use App\Mail\CustomerWelcomeMail;
use App\Models\UserModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Src\customer\user\application\useCases\SendCustomerWelcomeEmailUseCase;
use Tests\TestCase;

class SendCustomerWelcomeEmailUseCaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_sends_welcome_email_to_customer(): void
    {
        Mail::fake();

        $user = UserModel::create([
            'name' => 'Ana Torres',
            'email' => 'ana@test.com',
            'password' => bcrypt('password'),
        ]);

        $useCase = app(SendCustomerWelcomeEmailUseCase::class);
        $useCase->execute($user->id, 'Ana', 'Torres');

        Mail::assertSent(CustomerWelcomeMail::class, function (CustomerWelcomeMail $mail) use ($user) {
            return $mail->hasTo($user->email)
                && $mail->customerName === 'Ana Torres';
        });
    }

    public function test_does_not_crash_when_user_not_found(): void
    {
        Mail::fake();

        $useCase = app(SendCustomerWelcomeEmailUseCase::class);
        $useCase->execute(99999, 'Ana', 'Torres');

        Mail::assertNothingSent();
    }
}
