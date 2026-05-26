<?php

namespace src\admin\user\application\useCases;

use Src\admin\user\domain\exceptions\CannotDeleteSelfException;
use Src\admin\user\domain\exceptions\CannotDeleteSuperAdminException;
use Src\admin\user\domain\repositories\AdminRepositoryInterface;
use Src\shared\domain\repositories\EventPublisher;

class DeleteAdminUseCase
{
    public function __construct(
        private AdminRepositoryInterface $adminRepository,
        private EventPublisher $eventPublisher
    ) {}

    public function execute(int $id, ?int $currentAdminId = null): void
    {
        $admin = $this->adminRepository->findById($id);

        if ($admin->role()->isSuperAdmin()) {
            throw new CannotDeleteSuperAdminException;
        }

        if ($currentAdminId && $id === $currentAdminId) {
            throw new CannotDeleteSelfException;
        }

        // Domain event
        $admin->delete();

        $this->adminRepository->delete($admin);

        // Publish domain events
        foreach ($admin->releaseEvents() as $event) {
            $this->eventPublisher->publish($event);
        }
    }
}
