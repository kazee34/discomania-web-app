<?php

namespace Src\admin\user\application\useCases;

use Src\shared\domain\entities\User;
use Src\shared\domain\repositories\UserRepositoryInterface;

class FindUserByIdUseCase
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(int $id): ?User
    {
        return $this->userRepository->findById($id);
    }
}
