<?php

namespace Src\admin\user\application\useCases;

use Src\shared\domain\exceptions\UserNotFoundException;
use Src\shared\domain\repositories\EventPublisher;
use Src\shared\domain\repositories\UserRepositoryInterface;

class DeleteUserUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private EventPublisher $eventPublisher
    ) {}

    public function execute(int $id): void
    {
        $user = $this->userRepository->findById($id);

        if (! $user) {
            throw new UserNotFoundException($id);
        }

        $user->delete();

        $this->userRepository->delete($user->id());

        // Publish domain events
        foreach ($user->releaseEvents() as $event) {
            $this->eventPublisher->publish($event);
        }
    }
}
