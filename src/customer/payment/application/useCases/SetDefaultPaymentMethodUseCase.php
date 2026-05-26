<?php

namespace Src\customer\payment\application\useCases;

use App\Models\PaymentMethodModel;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;
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

        DB::transaction(function () use ($paymentMethodId, $customerId) {
            PaymentMethodModel::where('customer_id', $customerId)
                ->update(['is_default' => false]);

            PaymentMethodModel::where('id', $paymentMethodId)
                ->update(['is_default' => true]);
        });
    }
}
