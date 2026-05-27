<?php

namespace Src\customer\payment\application\useCases;

use Illuminate\Auth\Access\AuthorizationException;
use Src\customer\payment\domain\repositories\PaymentMethodRepositoryInterface;

final class SetDefaultPaymentMethodUseCase
{
    public function __construct(
        private PaymentMethodRepositoryInterface $repository,
    ) {}

    public function execute(int $paymentMethodId, int $customerId): void
    {
        $method = $this->repository->findById($paymentMethodId);

        if ($method->customerId() !== $customerId) {
            throw new AuthorizationException('Este método de pago no pertenece al cliente.');
        }

        $this->repository->setDefault($paymentMethodId, $customerId);
    }
}
