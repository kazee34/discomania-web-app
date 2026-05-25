<?php

namespace Src\admin\customer\application\useCases;

use Src\admin\customer\domain\repositories\AdminCustomerRepositoryInterface;
use Src\shared\domain\repositories\EventPublisher;

class AdminDeactivateCustomerUseCase
{
    public function __construct(
        private readonly AdminCustomerRepositoryInterface $repository,
        private readonly EventPublisher $eventPublisher,
    ) {}

    public function execute(int $customerId, bool $activate = false): void
    {
        $customer = $this->repository->findById($customerId);

        if (! $customer) {
            throw new \DomainException("Customer {$customerId} not found.");
        }

        $activate ? $customer->activate() : $customer->deactivate();

        $this->repository->update($customer);

        foreach ($customer->releaseEvents() as $event) {
            $this->eventPublisher->publish($event);
        }
    }
}
