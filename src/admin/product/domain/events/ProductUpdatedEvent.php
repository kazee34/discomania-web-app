<?php

namespace Src\admin\product\domain\events;

class ProductUpdatedEvent
{
    public function __construct(
        public readonly int $id,
        public readonly array $fields
    ) {}
}
