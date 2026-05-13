<?php

namespace Src\shared\domain\events;

class UserDeletedEvent
{
    public function __construct(
        public readonly int $id
    ) {}
}
