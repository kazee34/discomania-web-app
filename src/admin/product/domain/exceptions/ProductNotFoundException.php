<?php

namespace Src\admin\product\domain\exceptions;

use Exception;

class ProductNotFoundException extends Exception
{
    public function __construct(int $productId)
    {
        parent::__construct("Product with ID {$productId} not found.");
    }

    // public function __construct(string $slug)
    // {
    //     parent::__construct("Product with slug '{$slug}' not found.");
    // }
}