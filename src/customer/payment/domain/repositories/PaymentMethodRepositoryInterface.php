<?php

namespace Src\customer\payment\domain\repositories;

use Src\customer\payment\domain\entities\PaymentMethod;

interface PaymentMethodRepositoryInterface
{
    public function save(PaymentMethod $method): int;

    public function findById(int $id): PaymentMethod;

    public function findByCustomerId(int $customerId): array;

    public function delete(int $id): void;

    public function clearDefaultForCustomer(int $customerId): void;
}
