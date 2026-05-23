<?php

namespace Src\customer\user\application\useCases;

use Src\customer\user\domain\entities\Customer;
use Src\customer\user\domain\exceptions\CustomerNotFoundException;
use Src\customer\user\domain\repositories\CustomerRepositoryInterface;
use Src\customer\user\domain\valueObjects\CustomerPhone;
use Src\customer\user\domain\valueObjects\ShippingAddress;
use Src\shared\domain\repositories\EventPublisher;
use Src\shared\domain\valueObjects\UserName;

class UpdateCustomerProfileUseCase
{
    public function __construct(
        private CustomerRepositoryInterface $repository,
        private EventPublisher $eventPublisher,
    ) {}

    public function execute(
        int $userId,
        string $firstName,
        string $lastName,
        ?string $phone,
        string $shippingStreet,
        string $shippingStreetNumber,
        ?string $shippingApartment,
        string $shippingCity,
        string $shippingPostalCode,
        ?string $shippingStateProvince,
        string $shippingCountry,
    ): void {
        $customer = $this->repository->findByUserId($userId);

        if ($customer === null) {
            throw CustomerNotFoundException::byUserId($userId);
        }

        $updated = new Customer(
            id: $customer->id(),
            userId: $customer->userId(),
            firstName: new UserName($firstName),
            lastName: new UserName($lastName),
            phone: new CustomerPhone($phone),
            birthDate: $customer->birthDate(),
            dniNif: $customer->dniNif(),
            shippingAddress: new ShippingAddress(
                $shippingStreet,
                $shippingStreetNumber,
                $shippingApartment,
                $shippingCity,
                $shippingPostalCode,
                $shippingStateProvince ?? '',
                $shippingCountry,
                $customer->shippingAddress()->isoCountryCode(),
            ),
            taxInformation: $customer->taxInformation(),
            totalOrders: $customer->totalOrders(),
            preferences: $customer->preferences(),
            isActive: $customer->isActive(),
            createdAt: $customer->createdAt(),
        );

        $updated->updateFields(['firstName', 'lastName', 'phone', 'shippingAddress']);

        $this->repository->update($updated);

        foreach ($updated->releaseEvents() as $event) {
            $this->eventPublisher->publish($event);
        }
    }
}
