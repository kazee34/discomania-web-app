<?php

namespace Src\admin\user\domain\valueObjects;

enum AdminRole: string
{
    case SUPER_ADMIN = 'super_admin';
    case ADMIN = 'admin';
    case EDITOR = 'editor';

    public function value(): string
    {
        return $this->value;
    }

    public function isSuperAdmin(): bool
    {
        return $this->value === self::SUPER_ADMIN;
    }

    public function isAdmin(): bool
    {
        return $this->value === self::ADMIN;
    }

    public function isEditor(): bool
    {
        return $this->value === self::EDITOR;
    }
}
