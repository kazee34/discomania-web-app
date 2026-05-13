<?php

namespace Src\customer\user\domain\events;

class CustomerDeletedEvent
{
    public function __construct(
        public readonly int $customerId,
        public readonly int $userId,
    ) {}
}
