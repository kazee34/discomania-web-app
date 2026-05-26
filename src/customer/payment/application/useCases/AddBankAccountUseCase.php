<?php

namespace Src\customer\payment\application\useCases;

use Src\customer\payment\application\dto\PaymentMethodResult;
use Src\customer\payment\domain\entities\BankAccount;
use Src\customer\payment\domain\entities\PaymentMethod;
use Src\customer\payment\domain\repositories\PaymentMethodRepositoryInterface;
use Src\customer\payment\domain\valueObjects\IbanNumber;

final class AddBankAccountUseCase
{
    public function __construct(
        private PaymentMethodRepositoryInterface $repository,
    ) {}

    public function execute(
        int $customerId,
        string $ibanRaw,
        string $accountHolderName,
        ?string $bic,
        ?string $bankName,
        bool $setAsDefault,
    ): PaymentMethodResult {
        $iban = new IbanNumber($ibanRaw);

        if ($setAsDefault) {
            $this->repository->clearDefaultForCustomer($customerId);
        }

        $bankAccount = new BankAccount(
            id: null,
            paymentMethodId: null,
            accountHolderName: trim($accountHolderName),
            ibanMasked: $iban->masked(),
            ibanLastFour: $iban->lastFour(),
            ibanFull: $iban->value(),
            bic: $bic ? strtoupper(trim($bic)) : null,
            bankName: $bankName ? trim($bankName) : null,
        );

        $label = "IBAN •••• {$iban->lastFour()}";

        $method = PaymentMethod::createBankAccount(
            customerId: $customerId,
            label: $label,
            isDefault: $setAsDefault,
            bankAccount: $bankAccount,
        );

        $id = $this->repository->save($method);
        $method->setId($id);

        return PaymentMethodResult::fromEntity($method);
    }
}
