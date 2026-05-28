<?php

namespace Src\customer\order\application\listeners;

use Src\customer\order\application\useCases\SendOrderStatusUpdateEmailUseCase;
use Src\customer\order\domain\events\OrderStatusUpdatedEvent;

class SendOrderStatusUpdateEmailListener
{
    public function __construct(
        private SendOrderStatusUpdateEmailUseCase $useCase,
    ) {}

    public function handle(OrderStatusUpdatedEvent $event): void
    {
        $this->useCase->execute($event->customerId, $event->orderNumber, $event->newStatus, $event->totalAmount);
    }
}
