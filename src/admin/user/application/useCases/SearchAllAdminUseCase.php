<?php

namespace Src\admin\user\application\useCases;

use Src\admin\user\domain\repositories\AdminRepositoryInterface;

class SearchAllAdminUseCase
{
    private AdminRepositoryInterface $adminRepository;

    public function __construct(AdminRepositoryInterface $adminRepository)
    {
        $this->adminRepository = $adminRepository;
    }

    public function execute(): array
    {
        return $this->adminRepository->findAll();
    }
}
