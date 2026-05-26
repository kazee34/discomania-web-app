<?php

namespace Src\customer\order\domain\exceptions;

use Exception;

class OrderNotFoundException extends Exception
{
    public function __construct(string $message = 'Order not found.')
    {
        parent::__construct($message);
    }
}
