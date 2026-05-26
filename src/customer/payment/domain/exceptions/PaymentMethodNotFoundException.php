<?php

namespace Src\customer\payment\domain\exceptions;

use RuntimeException;

class PaymentMethodNotFoundException extends RuntimeException
{
    public function __construct(int $id)
    {
        parent::__construct("Payment method with id {$id} not found.");
    }
}
