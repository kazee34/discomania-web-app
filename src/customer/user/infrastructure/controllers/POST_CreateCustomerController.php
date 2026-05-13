<?php

namespace Src\customer\user\infrastructure\controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Src\customer\user\application\useCases\CreateCustomerRequest;
use Src\customer\user\application\useCases\CreateCustomerUseCase;
use Src\customer\user\infrastructure\repositories\EloquentCustomerRepository;

class POST_CreateCustomerController
{
    public function __invoke(Request $request): JsonResponse
    {
        $repository = new EloquentCustomerRepository;
        $useCase = new CreateCustomerUseCase($repository);

        try {
            $customer = $useCase->execute(new CreateCustomerRequest(
                userId: $request->integer('user_id'),
                firstName: $request->string('first_name')->trim()->value(),
                lastName: $request->string('last_name')->trim()->value(),
                phone: $request->string('phone')->trim()->value() ?: null,
                birthDate: $request->string('birth_date')->value() ?: null,
                dniNif: $request->string('dni_nif')->value() ?: null,
                shippingStreet: $request->string('shipping_street')->value() ?: null,
                shippingStreetNumber: $request->string('shipping_street_number')->value() ?: null,
                shippingApartment: $request->string('shipping_apartment')->value() ?: null,
                shippingCity: $request->string('shipping_city')->value() ?: null,
                shippingPostalCode: $request->string('shipping_postal_code')->value() ?: null,
                shippingStateProvince: $request->string('shipping_state_province')->value() ?: null,
                shippingCountry: $request->string('shipping_country')->value() ?: null,
                shippingIsoCountryCode: $request->string('shipping_iso_country_code')->value() ?: null,
                taxName: $request->string('tax_name')->value() ?: null,
                taxVatNumber: $request->string('tax_vat_number')->value() ?: null,
                preferredLanguage: $request->string('preferred_language')->value() ?: null,
                preferredCurrency: $request->string('preferred_currency')->value() ?: null,
                wishlist: $request->array('wishlist') ?: null,
            ));

            return response()->json([
                'success' => true,
                'message' => 'Customer created successfully',
                'data' => [
                    'id' => $customer->id(),
                    'user_id' => $customer->userId(),
                    'first_name' => $customer->firstName()->value(),
                    'last_name' => $customer->lastName()->value(),
                ],
            ], 201);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }
}
