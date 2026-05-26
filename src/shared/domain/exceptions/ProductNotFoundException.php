<?php

namespace Src\shared\domain\exceptions;

use Exception;

class ProductNotFoundException extends Exception
{
    public function __construct()
    {
        parent::__construct('The requested product could not be found.');
    }
}
