<?php

namespace Src\customer\order\application\useCases;

use Src\customer\order\application\dto\OrderResult;
use Src\customer\order\domain\repositories\OrderRepositoryInterface;
use Src\shared\domain\repositories\EventPublisher;

class CancelOrderUseCase
{
    public function __construct(
        private OrderRepositoryInterface $repository,
        private EventPublisher $eventPublisher
    ) {}

    public function execute(string $orderNumber): OrderResult
    {
        $order = $this->repository->findByOrderNumber($orderNumber);

        $order->cancel();

        $this->repository->updateStatus($order->id(), $order->status()->value);

        foreach ($order->releaseEvents() as $event) {
            $this->eventPublisher->publish($event);
        }
    
        return OrderResult::fromOrder($order);
    }
}
