<?php

namespace Src\admin\user\application\dto;

class CreateUserResult
{
    public function __construct(
        public readonly int $id,
        public readonly string $username,
        public readonly string $email
    ) {}
}
