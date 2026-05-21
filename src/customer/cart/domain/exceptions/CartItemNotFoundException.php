<?php

namespace Src\customer\cart\domain\exceptions;

use Exception;

class CartItemNotFoundException extends Exception
{
    public function __construct(string $message = 'Cart item not found.')
    {
        parent::__construct($message);
    }

    public static function byId(int $id): self
    {
        return new self("Cart item with id {$id} not found.");
    }
}
