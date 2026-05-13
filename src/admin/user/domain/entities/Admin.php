<?php

namespace Src\admin\user\domain\entities;

use Src\admin\user\domain\events\AdminActivatedEvent;
use Src\admin\user\domain\events\AdminCreatedEvent;
use Src\admin\user\domain\events\AdminDeactivatedEvent;
use Src\admin\user\domain\events\AdminDeletedEvent;
use Src\admin\user\domain\exceptions\CannotDeleteSuperAdminException;
use Src\admin\user\domain\valueObjects\AdminRole;
use src\admin\user\domain\valueObjects\UserPassword;
use Src\shared\domain\valueObjects\UserEmail;
use Src\shared\domain\valueObjects\UserName;

class Admin
{
    private array $events = [];

    public function __construct(
        private readonly ?int $id,
        private readonly int $userId,
        private AdminRole $role,
        private bool $isActive,
        private ?int $createdBy,
        private ?\DateTimeImmutable $createdAt,
        private ?\DateTimeImmutable $updatedAt
    ) {}

    public function id(): ?int
    {
        return $this->id;
    }

    public function userId(): int
    {
        return $this->userId;
    }

    public function role(): AdminRole
    {
        return $this->role;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function createdBy(): ?int
    {
        return $this->createdBy;
    }

    public function createdAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function events(): array
    {
        return $this->events;
    }

    public function fromPrimitives(
        int $id,
        int $userId,
        string $role,
        bool $isActive,
        int $createdBy
    ): Admin {
        return new Admin(
            id: $id,
            userId: $userId,
            role: AdminRole::from($role),
            isActive: $isActive,
            createdBy: $createdBy,
            createdAt: null,
            updatedAt: null,
        );
    }

    public function create(
        int $userId,
        AdminRole $role,
        UserName $username,
        UserEmail $email,
        UserPassword $password
    ): void {
        $this->recordEvent(new AdminCreatedEvent(
            userId: $userId,
            role: $role,
            username: $username,
            email: $email,
            password: $password
        ));
    }

    public function delete(): void
    {
        if ($this->role()->isSuperAdmin()) {
            throw new CannotDeleteSuperAdminException;
        }

        $this->isActive = false;

        $this->recordEvent(new AdminDeletedEvent(id: $this->id(), userId: $this->userId()));
    }

    public function deactivate(): void
    {
        if ($this->role()->isSuperAdmin()) {
            throw new CannotDeleteSuperAdminException;
        }

        $this->isActive = false;

        $this->recordEvent(new AdminDeactivatedEvent(id: $this->id(), userId: $this->userId()));
    }

    public function activate(): void
    {
        $this->isActive = true;

        $this->recordEvent(new AdminActivatedEvent(id: $this->id(), userId: $this->userId()));
    }

    public function recordEvent(object $event): void
    {
        $this->events[] = $event;
    }

    public function releaseEvents(): array
    {
        $eventsToRelease = $this->events;
        $this->events = [];

        return $eventsToRelease;
    }
}
