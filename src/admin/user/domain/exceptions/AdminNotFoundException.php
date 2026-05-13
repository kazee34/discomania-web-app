<?php

namespace Src\admin\user\domain\exceptions;

use Src\shared\domain\exceptions\UserNotFoundException;

class AdminNotFoundException extends UserNotFoundException
{
    public function __construct(int $id)
    {
        parent::__construct($id, 'Admin');
    }
}
