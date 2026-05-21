<?php

namespace Src\customer\user\infrastructure\controllers;

use App\Http\Controllers\Controller;
use App\Models\CustomerModel;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

final class GET_ProfileController extends Controller
{
    public function show(): Response
    {
        $user = Auth::user();
        $customer = CustomerModel::where('user_id', $user->id)->firstOrFail();

        return Inertia::render('profile/Index', [
            'profile' => [
                'email'                   => $user->email,
                'firstName'               => $customer->first_name,
                'lastName'                => $customer->last_name,
                'phone'                   => $customer->phone,
                'birthDate'               => $customer->birth_date?->format('Y-m-d'),
                'dniNif'                  => $customer->dni_nif,
                'shippingStreet'          => $customer->shipping_street,
                'shippingStreetNumber'    => $customer->shipping_street_number,
                'shippingApartment'       => $customer->shipping_apartment,
                'shippingCity'            => $customer->shipping_city,
                'shippingPostalCode'      => $customer->shipping_postal_code,
                'shippingStateProvince'   => $customer->shipping_state_province,
                'shippingCountry'         => $customer->shipping_country,
            ],
        ]);
    }
}