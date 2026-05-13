<?php

namespace Src\admin\user\domain\events;

class AdminActivatedEvent
{
    public function __construct(
        public readonly int $id,
        public readonly int $userId
    ) {}
}
