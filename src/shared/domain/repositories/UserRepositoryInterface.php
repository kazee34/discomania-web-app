<?php

namespace Src\shared\domain\repositories;

use Src\shared\domain\entities\User;
use Src\shared\domain\valueObjects\UserEmail;

interface UserRepositoryInterface
{
    public function findById(int $userId): ?User;

    public function findByEmail(UserEmail $email): ?User;

    public function save(User $user): void;

    public function delete(int $userId): void;
}
