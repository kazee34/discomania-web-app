<?php

namespace Src\admin\product\domain\events;

class ProductDeletedEvent
{
    public function __construct(
        public readonly int $productId
    ) {}
}