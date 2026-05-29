<?php

namespace Src\customer\product\application\useCases;

use Src\customer\order\domain\repositories\OrderRepositoryInterface;
use Src\customer\product\domain\repositories\ProductRepositoryInterface;

final class RestoreProductStockOnOrderCancelledUseCase
{
    public function __construct(
        private OrderRepositoryInterface $orderRepository,
        private ProductRepositoryInterface $productRepository,
    ) {}

    public function execute(int $orderId): void
    {
        $order = $this->orderRepository->findById($orderId);

        foreach ($order->items() as $item) {
            $this->productRepository->incrementStock($item->productId(), $item->quantity());
        }
    }
}
