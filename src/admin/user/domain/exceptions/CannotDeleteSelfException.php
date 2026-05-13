<?php

namespace Src\admin\user\domain\exceptions;

class CannotDeleteSelfException extends \DomainException
{
    public function __construct()
    {
        parent::__construct('Current user cannot delete itself.');
    }
}
