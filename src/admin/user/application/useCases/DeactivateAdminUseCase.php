<?php

namespace src\admin\user\application\useCases;

use Src\admin\user\domain\exceptions\CannotDeleteSuperAdminException;
use Src\admin\user\domain\repositories\AdminRepositoryInterface;
use Src\shared\domain\repositories\EventPublisher;

class DeactivateAdminUseCase
{
    public function __construct(
        private AdminRepositoryInterface $adminRepository,
        private EventPublisher $eventPublisher
    ) {}

    public function execute(int $id): void
    {
        $admin = $this->adminRepository->findById($id);

        if ($admin->role()->isSuperAdmin()) {
            throw new CannotDeleteSuperAdminException;
        }

        // Domain event
        $admin->deactivate();

        $this->adminRepository->save($admin);

        // Publish domain events
        foreach ($admin->releaseEvents() as $event) {
            $this->eventPublisher->publish($event);
        }
    }
}
