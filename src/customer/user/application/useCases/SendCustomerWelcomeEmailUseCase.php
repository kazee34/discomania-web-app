<?php

namespace Src\customer\user\application\useCases;

use Illuminate\Support\Facades\Log;
use Src\customer\user\domain\notifications\CustomerEmailNotifierInterface;
use Src\shared\domain\repositories\UserRepositoryInterface;

class SendCustomerWelcomeEmailUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private CustomerEmailNotifierInterface $notifier,
    ) {}

    public function execute(int $userId, string $firstName, string $lastName): void
    {
        $user = $this->userRepository->findById($userId);

        if (! $user) {
            Log::warning('SendCustomerWelcomeEmail: user not found', ['userId' => $userId]);

            return;
        }

        try {
            $this->notifier->sendWelcome($user->email()->value(), $firstName, $lastName);
        } catch (\Throwable $e) {
            Log::error('SendCustomerWelcomeEmail failed', [
                'userId' => $userId,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
