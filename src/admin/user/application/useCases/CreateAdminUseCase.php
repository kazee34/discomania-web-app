<?php

namespace src\admin\user\application\useCases;

use Src\admin\user\application\dto\CreateAdminResult;
use Src\admin\user\domain\entities\Admin;
use Src\admin\user\domain\repositories\AdminRepositoryInterface;
use Src\admin\user\domain\valueObjects\AdminRole;
use Src\shared\domain\repositories\EventPublisher;
use UnexpectedValueException;

class CreateAdminUseCase
{
    public function __construct(
        private AdminRepositoryInterface $adminRepository,
        private EventPublisher $eventPublisher
    ) {}

    public function execute(int $userId, string $role, bool $isActive): CreateAdminResult
    {
        logger()->info('CreateAdminUseCase -> Trying to create admin');
        $adminRole = AdminRole::tryFrom($role);

        if (! $adminRole) {
            throw new UnexpectedValueException('Provided admin role is not valid.');
        }

        $admin = new Admin(
            id: null,
            userId: $userId,
            role: AdminRole::from($role),
            isActive: $isActive,
            createdBy: null,
            createdAt: new \DateTimeImmutable,
            updatedAt: new \DateTimeImmutable
        );

        $this->adminRepository->save($admin);

        // Publish domain events
        foreach ($admin->releaseEvents() as $event) {
            $this->eventPublisher->publish($event);
        }

        return new CreateAdminResult(
            $admin->role()->value(),
            $admin->isActive()
        );
    }
}
