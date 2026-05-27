<?php

namespace Src\admin\user\application\useCases;

use Src\admin\user\domain\entities\Admin;
use Src\admin\user\domain\repositories\AdminRepositoryInterface;
use Src\shared\domain\repositories\UserRepositoryInterface;
use Src\shared\domain\valueObjects\UserEmail;

class FindAdminByEmailUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private AdminRepositoryInterface $adminRepository,
    ) {}

    public function execute(string $email): ?Admin
    {
        $user = $this->userRepository->findByEmail(new UserEmail($email));

        if (! $user) {
            return null;
        }

        $admin = $this->adminRepository->findByUserId($user->id());

        if (! $admin) {
            return null;
        }

        return $admin;
    }
}
