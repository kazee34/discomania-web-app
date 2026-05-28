<?php

namespace Src\customer\order\application\useCases;

use Illuminate\Support\Facades\Log;
use Src\customer\order\domain\notifications\OrderEmailNotifierInterface;
use Src\customer\order\domain\valueObjects\OrderStatus;
use Src\customer\user\domain\repositories\CustomerRepositoryInterface;
use Src\shared\domain\repositories\UserRepositoryInterface;

class SendOrderStatusUpdateEmailUseCase
{
    public function __construct(
        private CustomerRepositoryInterface $customerRepository,
        private UserRepositoryInterface $userRepository,
        private OrderEmailNotifierInterface $notifier,
    ) {}

    public function execute(int $customerId, string $orderNumber, OrderStatus $newStatus, float $totalAmount): void
    {
        $customer = $this->customerRepository->findById($customerId);

        if (! $customer) {
            Log::warning('SendOrderStatusUpdateEmail: customer not found', ['customerId' => $customerId]);

            return;
        }

        $user = $this->userRepository->findById($customer->userId());

        if (! $user) {
            Log::warning('SendOrderStatusUpdateEmail: user not found', ['userId' => $customer->userId()]);

            return;
        }

        try {
            $this->notifier->sendOrderStatusUpdated(
                $user->email()->value(),
                "{$customer->firstName()->value()} {$customer->lastName()->value()}",
                $orderNumber,
                $newStatus,
                $totalAmount,
            );
        } catch (\Throwable $e) {
            Log::error('SendOrderStatusUpdateEmail failed', [
                'customerId' => $customerId,
                'orderNumber' => $orderNumber,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
