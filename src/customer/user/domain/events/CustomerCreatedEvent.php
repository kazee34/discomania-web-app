<?php

namespace Src\customer\user\domain\events;

use Src\shared\domain\valueObjects\UserName;

class CustomerCreatedEvent
{
    public function __construct(
        public readonly ?int $customerId,
        public readonly int $userId,
        public readonly UserName $firstName,
        public readonly UserName $lastName,
    ) {}
}
