<?php

namespace Src\customer\order\application\listeners;

use Src\customer\order\application\useCases\SendOrderConfirmationEmailUseCase;
use Src\customer\order\domain\events\OrderCreatedEvent;

class SendOrderConfirmationEmailOnOrderCreatedListener
{
    public function __construct(
        private SendOrderConfirmationEmailUseCase $useCase,
    ) {}

    public function handle(OrderCreatedEvent $event): void
    {
        $this->useCase->execute($event->customerId, $event->orderNumber, $event->totalAmount);
    }
}
