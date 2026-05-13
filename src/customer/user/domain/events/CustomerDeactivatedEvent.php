<?php

namespace Src\customer\user\domain\events;

class CustomerDeactivatedEvent
{
    public function __construct(
        public readonly int $customerId,
        public readonly int $userId,
    ) {}
}
