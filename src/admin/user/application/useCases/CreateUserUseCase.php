<?php

namespace src\admin\user\application\useCases;

use Src\shared\domain\repositories\EventPublisher;
use Src\admin\user\application\dto\CreateUserResult;
use Src\admin\user\domain\valueObjects\AdminRole;
use Src\shared\domain\entities\User;
use Src\shared\domain\events\UserCreatedEvent;
use Src\shared\domain\repositories\UserRepositoryInterface;
use Src\shared\domain\valueObjects\UserEmail;
use Src\shared\domain\valueObjects\UserName;

class CreateUserUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository, 
        private EventPublisher $eventPublisher
    )
    { }

    public function execute(string $name, string $email, string $password, string $role): CreateUserResult
    {
        $user = User::create(
            userId: null,
            username: new UserName($name),
            email: new UserEmail($email),
            password: $password,
            role: ! empty($role) ? AdminRole::tryFrom($role) : null,
        );

        $this->userRepository->save($user);

        // Publish domain events
        foreach ($user->releaseEvents() as $event) {
            if ($event instanceof UserCreatedEvent) {
                $event->setId($user->id());
            }

            $this->eventPublisher->publish($event);
        }

        return new CreateUserResult(
            $user->username()->value(),
            $user->email()->value()
        );
    }
}