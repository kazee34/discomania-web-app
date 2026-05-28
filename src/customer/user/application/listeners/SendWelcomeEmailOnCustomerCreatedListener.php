<?php

namespace Src\customer\user\application\listeners;

use Src\customer\user\application\useCases\SendCustomerWelcomeEmailUseCase;
use Src\customer\user\domain\events\CustomerCreatedEvent;

class SendWelcomeEmailOnCustomerCreatedListener
{
    public function __construct(
        private SendCustomerWelcomeEmailUseCase $useCase,
    ) {}

    public function handle(CustomerCreatedEvent $event): void
    {
        $this->useCase->execute(
            $event->userId,
            $event->firstName->value(),
            $event->lastName->value(),
        );
    }
}
