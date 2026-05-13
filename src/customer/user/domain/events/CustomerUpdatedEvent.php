<?php

namespace Src\customer\user\domain\events;

class CustomerUpdatedEvent
{
    public function __construct(
        public readonly ?int $customerId,
        public readonly array $fieldsUpdated,
    ) {}
}
