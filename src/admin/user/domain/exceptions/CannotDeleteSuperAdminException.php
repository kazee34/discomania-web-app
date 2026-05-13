<?php

namespace Src\admin\user\domain\exceptions;

class CannotDeleteSuperAdminException extends \DomainException
{
    public function __construct()
    {
        parent::__construct('Current user lacks of permissions to delete super admin.');
    }
}
