<?php

namespace Src\admin\customer\application\useCases;

use Src\admin\customer\domain\repositories\AdminCustomerRepositoryInterface;

class AdminToggleVipUseCase
{
    public function __construct(
        private readonly AdminCustomerRepositoryInterface $repository,
    ) {}

    public function execute(int $customerId, bool $isVip): void
    {
        $customer = $this->repository->findById($customerId);

        if (! $customer) {
            throw new \DomainException("Customer {$customerId} not found.");
        }

        $customer->setVip($isVip);

        $this->repository->update($customer);
    }
}
