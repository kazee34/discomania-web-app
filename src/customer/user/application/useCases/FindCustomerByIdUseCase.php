<?php

namespace Src\customer\user\application\useCases;

use Src\customer\user\domain\entities\Customer;
use Src\customer\user\domain\exceptions\CustomerNotFoundException;
use Src\customer\user\domain\repositories\CustomerRepositoryInterface;

class FindCustomerByIdUseCase
{
    public function __construct(private readonly CustomerRepositoryInterface $repository) {}

    public function execute(int $id): Customer
    {
        $customer = $this->repository->findById($id);

        if (! $customer) {
            throw CustomerNotFoundException::byId($id);
        }

        return $customer;
    }
}
