<?php

namespace Src\shared\domain\events;

use Src\admin\user\domain\valueObjects\AdminRole;
use Src\shared\domain\valueObjects\UserEmail;
use Src\shared\domain\valueObjects\UserName;

class UserCreatedEvent
{
    public function __construct(
        public ?int $id,
        public readonly UserName $username,
        public readonly UserEmail $email,
        public readonly string $passwordHash,
        public readonly ?AdminRole $role = null,
    ) {}

    public function setId(int $id): void
    {
        $this->id = $id;
    }
}
