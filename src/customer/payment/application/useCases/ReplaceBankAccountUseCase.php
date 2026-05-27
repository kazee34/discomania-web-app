<?php

namespace Src\customer\payment\application\useCases;

use Illuminate\Auth\Access\AuthorizationException;
use Src\customer\payment\application\dto\PaymentMethodResult;
use Src\customer\payment\domain\repositories\PaymentMethodRepositoryInterface;

final class ReplaceBankAccountUseCase
{
    public function __construct(
        private PaymentMethodRepositoryInterface $repository,
        private AddBankAccountUseCase $addBankAccount,
        private RemovePaymentMethodUseCase $remove,
    ) {}

    public function execute(
        int $methodId,
        int $customerId,
        string $iban,
        string $accountHolder,
        ?string $bankName,
        bool $setAsDefault,
    ): PaymentMethodResult {
        $method = $this->repository->findById($methodId);

        if ($method->customerId() !== $customerId) {
            throw new AuthorizationException('Este método de pago no pertenece al cliente.');
        }

        $wasDefault = $method->isDefault();

        $this->remove->execute($methodId, $customerId);

        return $this->addBankAccount->execute(
            customerId: $customerId,
            ibanRaw: $iban,
            accountHolderName: $accountHolder,
            bankName: $bankName,
            setAsDefault: $setAsDefault || $wasDefault,
        );
    }
}
