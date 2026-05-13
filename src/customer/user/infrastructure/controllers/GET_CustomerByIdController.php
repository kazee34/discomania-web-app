<?php

namespace Src\customer\user\infrastructure\controllers;

use Illuminate\Http\JsonResponse;
use Src\customer\user\application\useCases\GetCustomerByIdUseCase;
use Src\customer\user\domain\exceptions\CustomerNotFoundException;
use Src\customer\user\infrastructure\repositories\EloquentCustomerRepository;

class GET_CustomerByIdController
{
    public function __invoke(int $id): JsonResponse
    {
        $repository = new EloquentCustomerRepository;
        $useCase = new GetCustomerByIdUseCase($repository);

        try {
            $customer = $useCase->execute($id);

            return response()->json([
                'success' => true,
                'data' => $this->formatCustomer($customer),
            ]);
        } catch (CustomerNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 404);
        }
    }

    private function formatCustomer($customer): array
    {
        $address = $customer->shippingAddress();
        $tax = $customer->taxInformation();
        $prefs = $customer->preferences();

        return [
            'id' => $customer->id(),
            'user_id' => $customer->userId(),
            'first_name' => $customer->firstName()->value(),
            'last_name' => $customer->lastName()->value(),
            'phone' => $customer->phone()->value(),
            'birth_date' => $customer->birthDate(),
            'dni_nif' => $customer->dniNif()->value(),
            'shipping_address' => [
                'street' => $address->street(),
                'street_number' => $address->streetNumber(),
                'apartment' => $address->apartment(),
                'city' => $address->city(),
                'postal_code' => $address->postalCode(),
                'state_province' => $address->stateProvince(),
                'country' => $address->country(),
                'iso_country_code' => $address->isoCountryCode(),
            ],
            'tax_information' => [
                'name' => $tax->name(),
                'vat_number' => $tax->vatNumber(),
            ],
            'total_orders' => $customer->totalOrders(),
            'preferences' => [
                'preferred_language' => $prefs->preferredLanguage(),
                'preferred_currency' => $prefs->preferredCurrency(),
                'wishlist' => $prefs->wishlist(),
            ],
            'created_at' => $customer->createdAt()?->toIso8601String(),
            'updated_at' => $customer->updatedAt()?->toIso8601String(),
        ];
    }
}
