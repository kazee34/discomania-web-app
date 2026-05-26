<?php

namespace Src\customer\user\infrastructure\controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Src\customer\user\application\useCases\FindCustomerByUserIdUseCase;

final class GET_ProfileController extends Controller
{
    public function __construct(
        private FindCustomerByUserIdUseCase $findCustomer,
    ) {}

    public function show(): Response
    {
        $customer = $this->findCustomer->execute(Auth::id());
        $address = $customer->shippingAddress();

        return Inertia::render('profile/Index', [
            'profile' => [
                'email' => Auth::user()->email,
                'firstName' => $customer->firstName()->value(),
                'lastName' => $customer->lastName()->value(),
                'phone' => $customer->phone()->value(),
                'birthDate' => $customer->birthDate(),
                'dniNif' => $customer->dniNif()->value(),
                'shippingStreet' => $address->street(),
                'shippingStreetNumber' => $address->streetNumber(),
                'shippingApartment' => $address->apartment(),
                'shippingCity' => $address->city(),
                'shippingPostalCode' => $address->postalCode(),
                'shippingStateProvince' => $address->stateProvince(),
                'shippingCountry' => $address->country(),
            ],
        ]);
    }
}
