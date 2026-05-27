<?php

namespace Src\admin\user\domain\repositories;

use Src\admin\user\domain\entities\Admin;

interface AdminRepositoryInterface
{
    public function findById(int $id): ?Admin;

    public function findByUserId(int $userId): ?Admin;

    public function findAll(): array;

    public function save(Admin $admin): void;

    public function delete(Admin $admin): void;

    public function exists(int $id): bool;
}
