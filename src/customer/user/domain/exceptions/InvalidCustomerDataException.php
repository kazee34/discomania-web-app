<?php

namespace Src\customer\user\domain\exceptions;

use Exception;

class InvalidCustomerDataException extends Exception
{
    public function __construct(string $message)
    {
        parent::__construct("Invalid customer data: {$message}");
    }
}
