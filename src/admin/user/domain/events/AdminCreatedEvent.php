<?php

namespace Src\admin\user\domain\events;

use Src\admin\user\domain\valueObjects\AdminRole;
use Src\admin\user\domain\valueObjects\UserPassword;
use Src\shared\domain\valueObjects\UserEmail;
use Src\shared\domain\valueObjects\UserName;

class AdminCreatedEvent
{
    public function __construct(
        public readonly int $userId,
        public readonly AdminRole $role,
        public readonly UserName $username,
        public readonly UserEmail $email,
        public readonly UserPassword $password
    ) {}
}
