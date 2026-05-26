<?php

namespace Src\customer\cart\domain\events;

class CartCreatedEvent
{
    public function __construct(
        public readonly ?int $customerId,
        public readonly ?string $sessionId,
    ) {}
}
