<?php

namespace Src\admin\user\application\useCases;

use Src\admin\user\domain\entities\Admin;
use Src\admin\user\domain\repositories\AdminRepositoryInterface;

class FindAdminByUserIdUseCase
{
    private AdminRepositoryInterface $adminRepository;

    public function __construct(AdminRepositoryInterface $adminRepository)
    {
        $this->adminRepository = $adminRepository;
    }

    public function execute(int $userId): ?Admin
    {
        return $this->adminRepository->findByUserId($userId);
    }
}
