<?php

namespace Src\customer\user\infrastructure\controllers;

use App\Http\Controllers\Controller;
use App\Models\CustomerModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

final class PUT_UpdateProfileController extends Controller
{
    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'firstName'             => ['required', 'string', 'max:100'],
            'lastName'              => ['required', 'string', 'max:100'],
            'phone'                 => ['nullable', 'string', 'max:20'],
            'shippingStreet'        => ['nullable', 'string', 'max:200'],
            'shippingStreetNumber'  => ['nullable', 'string', 'max:20'],
            'shippingApartment'     => ['nullable', 'string', 'max:50'],
            'shippingCity'          => ['nullable', 'string', 'max:100'],
            'shippingPostalCode'    => ['nullable', 'string', 'max:20'],
            'shippingStateProvince' => ['nullable', 'string', 'max:100'],
            'shippingCountry'       => ['nullable', 'string', 'max:100'],
        ]);

        $user = Auth::user();
        $customer = CustomerModel::where('user_id', $user->id)->firstOrFail();

        $customer->update([
            'first_name'              => $data['firstName'],
            'last_name'               => $data['lastName'],
            'phone'                   => $data['phone'] ?? null,
            'shipping_street'         => $data['shippingStreet'] ?? null,
            'shipping_street_number'  => $data['shippingStreetNumber'] ?? null,
            'shipping_apartment'      => $data['shippingApartment'] ?? null,
            'shipping_city'           => $data['shippingCity'] ?? null,
            'shipping_postal_code'    => $data['shippingPostalCode'] ?? null,
            'shipping_state_province' => $data['shippingStateProvince'] ?? null,
            'shipping_country'        => $data['shippingCountry'] ?? null,
        ]);

        return back()->with('success', 'Perfil actualizado correctamente.');
    }
}