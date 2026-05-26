<?php

namespace Src\customer\payment\application\useCases;

use Src\customer\payment\application\dto\PaymentMethodResult;
use Src\customer\payment\domain\repositories\PaymentMethodRepositoryInterface;

final class ListPaymentMethodsUseCase
{
    public function __construct(
        private PaymentMethodRepositoryInterface $repository,
    ) {}

    /** @return PaymentMethodResult[] */
    public function execute(int $customerId): array
    {
        $methods = $this->repository->findByCustomerId($customerId);

        return array_map(
            fn ($m) => PaymentMethodResult::fromEntity($m),
            $methods,
        );
    }
}
