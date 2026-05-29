<?php

namespace Src\customer\order\application\useCases;

use Illuminate\Support\Facades\Log;
use Src\customer\order\application\dto\OrderResult;
use Src\customer\order\domain\notifications\OrderEmailNotifierInterface;
use Src\customer\order\domain\repositories\OrderRepositoryInterface;
use Src\customer\payment\domain\repositories\OrderPaymentRepositoryInterface;
use Src\customer\user\domain\repositories\CustomerRepositoryInterface;
use Src\shared\domain\repositories\UserRepositoryInterface;

final class SendOrderConfirmationEmailUseCase
{
    public function __construct(
        private CustomerRepositoryInterface $customerRepository,
        private UserRepositoryInterface $userRepository,
        private OrderRepositoryInterface $orderRepository,
        private OrderPaymentRepositoryInterface $paymentRepository,
        private OrderEmailNotifierInterface $notifier,
    ) {}

    public function execute(int $customerId, string $orderNumber, float $totalAmount): void
    {
        try {
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

            $order = OrderResult::fromOrder($this->orderRepository->findByOrderNumber($orderNumber));
            $payment = $this->paymentRepository->findByOrderId($order->id);

            $this->notifier->sendOrderConfirmation(
                $user->email()->value(),
                "{$customer->firstName()->value()} {$customer->lastName()->value()}",
                $order,
                $customer->shippingAddress()->toArray(),
                $payment?->paymentSummary() ?? '—',
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
