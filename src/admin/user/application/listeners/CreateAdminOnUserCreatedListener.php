<?php

namespace Src\admin\user\application\listeners;

use Src\admin\user\application\useCases\CreateAdminUseCase;
use Src\shared\domain\events\UserCreatedEvent;

class CreateAdminOnUserCreatedListener
{
    public function __construct(
        private CreateAdminUseCase $createAdminUseCase
    ) {}

    public function handle(UserCreatedEvent $event): void
    {
        logger()->debug('Listener triggered for user: ', ['event' => $event]);
        try {
            // Only create admin if role is provided
            if ($event->role === null) {
                logger()->error('No role provided for user, skipping admin creation', [
                    'user_id' => $event->id,
                ]);

                throw new \Exception('No role provided for user, skipping admin creation');
            }

            $this->createAdminUseCase->execute(
                userId: $event->id,
                role: $event->role->value(),
                isActive: true
            );

            logger()->debug('Creating admin after user creation', [
                'user_id' => $event->id,
                'role' => $event->role->value(),
            ]);
        } catch (\Exception $e) {
            logger()->error('Failed to create admin after user registration', [
                'user_id' => $event->id,
                'error' => $e->getMessage(),
            ]);
            throw new \Exception($e);
        }
    }
}
