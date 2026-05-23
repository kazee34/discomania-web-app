<?php

namespace Src\admin\customer\application\useCases;

use Src\customer\user\domain\entities\Customer;
use Src\admin\customer\domain\repositories\AdminCustomerRepositoryInterface;

class AdminGetCustomerUseCase
{
    public function __construct(
        private readonly AdminCustomerRepositoryInterface $repository,
    ) {}

    public function execute(int $id): Customer
    {
        $customer = $this->repository->findById($id);

        if (! $customer) {
            throw new \DomainException("Customer {$id} not found.");
        }

        return $customer;
    }
}
