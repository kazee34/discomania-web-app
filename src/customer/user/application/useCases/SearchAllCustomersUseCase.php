<?php

namespace Src\customer\user\application\useCases;

use Src\customer\user\domain\repositories\CustomerRepositoryInterface;

class SearchAllCustomersUseCase
{
    public function __construct(
        private readonly CustomerRepositoryInterface $repository
    ) { }

    public function execute(): array
    {
        return $this->repository->searchAll();
    }
}