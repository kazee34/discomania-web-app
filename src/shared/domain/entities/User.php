<?php

namespace Src\shared\domain\entities;

use Src\admin\user\domain\valueObjects\AdminRole;
use Src\admin\user\domain\valueObjects\UserPassword;
use Src\shared\domain\events\UserCreatedEvent;
use Src\shared\domain\events\UserDeletedEvent;
use Src\shared\domain\valueObjects\UserEmail;
use Src\shared\domain\valueObjects\UserName;

class User
{
    private array $events = [];

    public function __construct(
        private ?int $id,
        private UserName $username,
        private UserEmail $email,
        private readonly UserPassword $password
    ) {}

    public static function create(
        ?int $userId,
        UserName $username,
        UserEmail $email,
        string $password,
        ?AdminRole $role = null,
    ): self {
        $user = new self($userId, $username, $email, UserPassword::fromPlain($password));

        $user->recordEvent(new UserCreatedEvent(
            id: $userId,
            username: $username,
            email: $email,
            passwordHash: $password,
            role: $role,
        ));

        return $user;
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function username(): UserName
    {
        return $this->username;
    }

    public function email(): UserEmail
    {
        return $this->email;
    }

    public function passwordHash(): string
    {
        return $this->password->hash();
    }

    public function recordEvent(object $event): void
    {
        $this->events[] = $event;
    }

    public function delete(): void
    {
        $this->recordEvent(new UserDeletedEvent(id: $this->id()));
    }

    public function releaseEvents(): array
    {
        $eventsToRelease = $this->events;
        $this->events = [];

        return $eventsToRelease;
    }
}
