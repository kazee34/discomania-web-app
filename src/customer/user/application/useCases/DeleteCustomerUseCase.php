<?php

namespace Src\customer\user\application\useCases;

use Src\customer\user\domain\repositories\CustomerRepositoryInterface;
use Src\shared\domain\repositories\EventPublisher;

class DeleteCustomerUseCase
{
    public function __construct(
        private CustomerRepositoryInterface $repository,
        private EventPublisher $eventPublisher
    ) {}

    public function execute(int $customerId): void
    {
        $customer = $this->repository->findById($customerId);

        if (! $customer) {
            throw new \Exception('Customer not found');
        }

        $customer->delete();

        $this->repository->delete($customerId);

        foreach ($customer->releaseEvents() as $event) {
            $this->eventPublisher->publish($event);
        }
    }
}
