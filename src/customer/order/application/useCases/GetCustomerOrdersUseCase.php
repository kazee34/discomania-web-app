<?php

namespace Src\customer\order\application\useCases;

use Src\customer\order\application\dto\OrderResult;
use Src\customer\order\domain\repositories\OrderRepositoryInterface;

class GetCustomerOrdersUseCase
{
    public function __construct(
        private OrderRepositoryInterface $repository,
    ) {}

    public function execute(int $customerId): array
    {
        $orders = $this->repository->findByCustomerId($customerId);

        return array_map(fn ($order) => OrderResult::fromOrder($order), $orders);
    }
}
