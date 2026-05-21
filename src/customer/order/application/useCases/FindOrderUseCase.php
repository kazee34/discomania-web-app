<?php

namespace Src\customer\order\application\useCases;

use Src\customer\order\application\dto\OrderResult;
use Src\customer\order\domain\repositories\OrderRepositoryInterface;

class FindOrderUseCase
{
    public function __construct(
        private OrderRepositoryInterface $repository,
    ) {}

    public function execute(string $orderNumber): OrderResult
    {
        $order = $this->repository->findByOrderNumber($orderNumber);

        return OrderResult::fromOrder($order);
    }
}