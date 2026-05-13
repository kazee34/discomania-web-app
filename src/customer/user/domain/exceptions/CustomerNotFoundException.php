<?php

namespace Src\customer\user\domain\exceptions;

use Exception;

class CustomerNotFoundException extends Exception
{
    public static function byId(int $id): self
    {
        return new self("Customer with ID {$id} not found");
    }

    public static function byUserId(int $userId): self
    {
        return new self("Customer with User ID {$userId} not found");
    }
}
