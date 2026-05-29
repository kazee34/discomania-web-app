<?php

namespace Src\customer\product\application\listeners;

use Src\customer\order\domain\events\OrderCancelledEvent;
use Src\customer\product\application\useCases\RestoreProductStockOnOrderCancelledUseCase;

final class RestoreProductStockOnOrderCancelledListener
{
    public function __construct(
        private RestoreProductStockOnOrderCancelledUseCase $useCase,
    ) {}

    public function handle(OrderCancelledEvent $event): void
    {
        $this->useCase->execute($event->orderId);
    }
}
