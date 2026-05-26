<?php

namespace Src\customer\user\application\useCases;

use Src\customer\user\domain\entities\Customer;
use Src\customer\user\domain\exceptions\CustomerNotFoundException;
use Src\customer\user\domain\repositories\CustomerRepositoryInterface;

class FindCustomerByUserIdUseCase
{
    public function __construct(
        private readonly CustomerRepositoryInterface $repository
    ) {}

    public function execute(int $userId): Customer
    {
        $customer = $this->repository->findByUserId($userId);

        if (! $customer) {
            throw CustomerNotFoundException::byUserId($userId);
        }

        return $customer;
    }
}
