<?php

namespace Src\admin\user\application\listeners;

use src\admin\user\application\useCases\DeleteUserUseCase;
use Src\admin\user\domain\events\AdminDeletedEvent;

class DeleteUserOnAdminDeletedListener
{
    public function __construct(
        private DeleteUserUseCase $deleteUserUseCase
    ) {}

    public function handle(AdminDeletedEvent $event): void
    {
        try {
            $this->deleteUserUseCase->execute($event->userId);

            logger()->info('User deleted after admin deletion', [
                'admin_id' => $event->id,
                'user_id' => $event->userId,
            ]);

        } catch (\Exception $e) {
            logger()->error('Failed to delete user after admin deletion', [
                'admin_id' => $event->id,
                'user_id' => $event->userId,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
