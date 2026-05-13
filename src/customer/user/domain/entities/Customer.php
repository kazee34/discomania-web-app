<?php

namespace Src\customer\user\domain\entities;

use Src\customer\user\domain\events\CustomerCreatedEvent;
use Src\customer\user\domain\events\CustomerDeactivatedEvent;
use Src\customer\user\domain\events\CustomerDeletedEvent;
use Src\customer\user\domain\events\CustomerUpdatedEvent;
use Src\customer\user\domain\valueObjects\CustomerDNI;
use Src\customer\user\domain\valueObjects\CustomerPhone;
use Src\customer\user\domain\valueObjects\CustomerPreferences;
use Src\customer\user\domain\valueObjects\ShippingAddress;
use Src\customer\user\domain\valueObjects\TaxInformation;
use Src\shared\domain\valueObjects\UserName;

class Customer
{
    private array $events = [];

    public function __construct(
        private readonly ?int $id,
        private readonly int $userId,
        private readonly UserName $firstName,
        private readonly UserName $lastName,
        private readonly CustomerPhone $phone,
        private readonly ?string $birthDate,
        private readonly CustomerDNI $dniNif,
        private readonly ShippingAddress $shippingAddress,
        private readonly TaxInformation $taxInformation,
        private readonly int $totalOrders,
        private readonly CustomerPreferences $preferences,
        private bool $isActive = true,
        private readonly ?\DateTimeImmutable $createdAt = null,
        private readonly ?\DateTimeImmutable $updatedAt = null,
    ) {}

    public static function create(
        int $userId,
        UserName $firstName,
        UserName $lastName,
        CustomerPhone $phone,
        ?string $birthDate,
        CustomerDNI $dniNif,
        ShippingAddress $shippingAddress,
        TaxInformation $taxInformation,
        CustomerPreferences $preferences,
    ): self {
        $customer = new self(
            id: null,
            userId: $userId,
            firstName: $firstName,
            lastName: $lastName,
            phone: $phone,
            birthDate: $birthDate,
            dniNif: $dniNif,
            shippingAddress: $shippingAddress,
            taxInformation: $taxInformation,
            totalOrders: 0,
            preferences: $preferences,
            createdAt: new \DateTimeImmutable,
            updatedAt: new \DateTimeImmutable,
        );

        $customer->recordEvent(new CustomerCreatedEvent(
            customerId: null,
            userId: $userId,
            firstName: $firstName,
            lastName: $lastName,
        ));

        return $customer;
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function userId(): int
    {
        return $this->userId;
    }

    public function firstName(): UserName
    {
        return $this->firstName;
    }

    public function lastName(): UserName
    {
        return $this->lastName;
    }

    public function phone(): CustomerPhone
    {
        return $this->phone;
    }

    public function birthDate(): ?string
    {
        return $this->birthDate;
    }

    public function dniNif(): CustomerDNI
    {
        return $this->dniNif;
    }

    public function shippingAddress(): ShippingAddress
    {
        return $this->shippingAddress;
    }

    public function taxInformation(): TaxInformation
    {
        return $this->taxInformation;
    }

    public function totalOrders(): int
    {
        return $this->totalOrders;
    }

    public function preferences(): CustomerPreferences
    {
        return $this->preferences;
    }

    public function createdAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function deactivate(): void
    {
        $this->isActive = false;

        $this->recordEvent(new CustomerDeactivatedEvent(
            customerId: $this->id,
            userId: $this->userId,
        ));
    }

    public function activate(): void
    {
        $this->isActive = true;
    }

    public function delete(): void
    {
        $this->recordEvent(new CustomerDeletedEvent(
            customerId: $this->id,
            userId: $this->userId,
        ));
    }

    public function updateFields(array $fieldsUpdated): void
    {
        $this->recordEvent(new CustomerUpdatedEvent(
            customerId: $this->id,
            fieldsUpdated: $fieldsUpdated,
        ));
    }

    public function recordEvent(object $event): void
    {
        $this->events[] = $event;
    }

    public function releaseEvents(): array
    {
        $events = $this->events;
        $this->events = [];

        return $events;
    }
}
