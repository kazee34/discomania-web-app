<?php

namespace Src\customer\order\application\useCases;

use Illuminate\Support\Facades\Log;
use Src\customer\order\domain\notifications\OrderEmailNotifierInterface;
use Src\customer\user\domain\repositories\CustomerRepositoryInterface;
use Src\shared\domain\repositories\UserRepositoryInterface;

class SendOrderConfirmationEmailUseCase
{
    public function __construct(
        private CustomerRepositoryInterface $customerRepository,
        private UserRepositoryInterface $userRepository,
        private OrderEmailNotifierInterface $notifier,
    ) {}

    public function execute(int $customerId, string $orderNumber, float $totalAmount): void
    {
        $customer = $this->customerRepository->findById($customerId);

        if (! $customer) {
            Log::warning('SendOrderConfirmationEmail: customer not found', ['customerId' => $customerId]);

            return;
        }

        $user = $this->userRepository->findById($customer->userId());

        if (! $user) {
            Log::warning('SendOrderConfirmationEmail: user not found', ['userId' => $customer->userId()]);

            return;
        }

        try {
            $this->notifier->sendOrderConfirmation(
                $user->email()->value(),
                "{$customer->firstName()->value()} {$customer->lastName()->value()}",
                $orderNumber,
                $totalAmount,
            );
        } catch (\Throwable $e) {
            Log::error('SendOrderConfirmationEmail failed', [
                'customerId' => $customerId,
                'orderNumber' => $orderNumber,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
