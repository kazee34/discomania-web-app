<?php

namespace Src\admin\product\domain\valueObjects;

// TBD
class ProductPrice
{
    public function __construct(private readonly float $value) {}

    public function value(): float
    {
        return $this->value;
    }
}
