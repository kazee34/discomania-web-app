<?php

namespace Src\customer\order\domain\repositories;

use Src\customer\order\domain\entities\Order;

interface OrderRepositoryInterface
{
    public function save(Order $order): int;

    public function findById(int $id): Order;

    public function findByOrderNumber(string $orderNumber): Order;

    public function findAll(): array;

    public function findByCustomerId(int $customerId): array;

    public function updateStatus(int $orderId, string $status): void;
}
