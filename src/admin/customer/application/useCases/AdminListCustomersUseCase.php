<?php

namespace Src\admin\customer\application\useCases;

use Src\admin\customer\domain\repositories\AdminCustomerRepositoryInterface;

class AdminListCustomersUseCase
{
    public function __construct(
        private readonly AdminCustomerRepositoryInterface $repository,
    ) {}

    public function execute(): array
    {
        return $this->repository->searchAll();
    }
}
