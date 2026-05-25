<?php

namespace Src\admin\customer\domain\repositories;

use Src\customer\user\domain\entities\Customer;

interface AdminCustomerRepositoryInterface
{
    public function searchAll(): array;

    public function findById(int $id): ?Customer;

    public function update(Customer $customer): void;
}
