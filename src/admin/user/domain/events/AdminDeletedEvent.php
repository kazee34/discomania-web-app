<?php

namespace Src\admin\user\domain\events;

class AdminDeletedEvent
{
    public function __construct(
        public readonly int $id,
        public readonly int $userId
    ) {}
}
