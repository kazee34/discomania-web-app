<?php

namespace Src\admin\user\application\dto;

class CreateAdminResult
{
    public function __construct(
        public readonly string $role,
        public readonly string $isActive
    ) {}
}
