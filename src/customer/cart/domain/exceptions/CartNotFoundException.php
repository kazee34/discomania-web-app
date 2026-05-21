<?php

namespace Src\customer\cart\domain\exceptions;

use Exception;

class CartNotFoundException extends Exception
{
    public function __construct(string $message = 'Cart not found.')
    {
        parent::__construct($message);
    }

    public static function byId(int $id): self
    {
        return new self("Cart with id {$id} not found.");
    }

    public static function byToken(string $token): self
    {
        return new self("Cart with token {$token} not found.");
    }
}
