<?php

namespace Src\customer\user\application\useCases;

use Src\customer\user\application\dto\CreateCustomerRequest;
use Src\customer\user\domain\entities\Customer;
use Src\customer\user\domain\repositories\CustomerRepositoryInterface;
use Src\customer\user\domain\valueObjects\CustomerDNI;
use Src\customer\user\domain\valueObjects\CustomerPhone;
use Src\customer\user\domain\valueObjects\CustomerPreferences;
use Src\customer\user\domain\valueObjects\ShippingAddress;
use Src\customer\user\domain\valueObjects\TaxInformation;
use Src\shared\domain\valueObjects\UserName;
use Src\shared\domain\repositories\EventPublisher;

class CreateCustomerUseCase
{
    public function __construct(
        private CustomerRepositoryInterface $repository, 
        private EventPublisher $eventPublisher
    ) {}

    public function execute(CreateCustomerRequest $request): Customer
    {
        $customer = Customer::create(
            userId: $request->userId,
            firstName: new UserName($request->firstName),
            lastName: new UserName($request->lastName),
            phone: new CustomerPhone($request->phone),
            birthDate: $request->birthDate,
            dniNif: new CustomerDNI($request->dniNif),
            shippingAddress: new ShippingAddress(
                $request->shippingStreet,
                $request->shippingStreetNumber,
                $request->shippingApartment,
                $request->shippingCity,
                $request->shippingPostalCode,
                $request->shippingStateProvince ?? '',
                $request->shippingCountry,
                $request->shippingIsoCountryCode ?? 'ES',
            ),
            taxInformation: new TaxInformation(
                $request->taxName,
                $request->taxVatNumber,
            ),
            preferences: new CustomerPreferences(
                $request->preferredLanguage,
                $request->preferredCurrency,
                $request->wishlist,
            ),
        );

        $customer->setVip($request->isVip);

        $this->repository->save($customer);

        foreach ($customer->releaseEvents() as $event) {
            $this->eventPublisher->publish($event);
        }

        return $customer;
    }
}